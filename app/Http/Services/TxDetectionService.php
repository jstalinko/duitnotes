<?php

namespace App\Http\Services;

class TxDetectionService
{
    /**
     * Keywords that identify an incoming transaction.
     * Order matters: put longer phrases first to prevent partial matches.
     */
    protected $incomeKeywords = [
        'pemasukan',
        'uang masuk',
        'transfer dari',
        'trf dari',
        'tf dari',
        'dapet dana',
        'dapet uang',
        'terima',
        'income',
        'gaji',
        'masuk',
        'dapet',
        'dapat'
    ];

    /**
     * Keywords that identify an outgoing transaction.
     * Order matters: put longer phrases first to prevent partial matches.
     */
    protected $expenseKeywords = [
        'pengeluaran',
        'transfer ke',
        'trf ke',
        'tf ke',
        'belanja',
        'expense',
        'keluar',
        'bayar',
        'jajan',
        'beli',
        'buat'
    ];

    /**
     * Smart auto-detection of text to categorize it as an in or out transaction.
     * Matches keywords, extracts amounts with support for 'k', 'rb', 'jt' suffixes,
     * and uses the remaining text as the description.
     *
     * @param string $text
     * @return array|null Returns ['type' => 'in'|'out', 'description' => string, 'amount' => float] or null
     */
    public function detect(string $text): ?array
    {
        $text = strtolower(trim($text));
        $type = null;

        // 1. Identify Type (In/Out) by searching for keywords
        foreach ($this->incomeKeywords as $keyword) {
            $pattern = '/\b' . preg_quote($keyword, '/') . '\b/i';
            if (preg_match($pattern, $text)) {
                $type = 'in';
                // Stop searching once type is found, keep keyword in text for description
                break;
            }
        }

        if (!$type) {
            foreach ($this->expenseKeywords as $keyword) {
                $pattern = '/\b' . preg_quote($keyword, '/') . '\b/i';
                if (preg_match($pattern, $text)) {
                    $type = 'out';
                    // Stop searching once type is found, keep keyword in text for description
                    break;
                }
            }
        }

        // Return null if neither income nor expense keywords were found
        if (!$type) {
            return null;
        }

        // 2. Extract Amount
        // Supports formats like: 50.000, 50,000, 50k, 50 ribu, 1.5jt, Rp 50.000, 50000
        $amountPattern = '/(?:rp\.?\s*)?(\d+(?:[\.,]\d+)*)\s*(k|rb|ribu|jt|juta|m|miliya?r)?\b/i';
        $amount = null;
        $bestMatchText = null;

        if (preg_match_all($amountPattern, $text, $matches, PREG_SET_ORDER)) {
            $bestScore = -1;

            foreach ($matches as $match) {
                $matchedAmountText = $match[0];
                $rawNum = $match[1];
                $multiplier = strtolower($match[2] ?? '');

                $hasRp = stripos($matchedAmountText, 'rp') !== false;

                if ($multiplier) {
                    // With a multiplier, commas/dots usually act as decimal points (e.g. 1.5 jt or 1,5 jt)
                    $numStr = str_replace(',', '.', $rawNum);
                    $num = (float) $numStr;
                    switch ($multiplier) {
                        case 'k':
                        case 'rb':
                        case 'ribu':
                            $amountCandidate = $num * 1000;
                            break;
                        case 'jt':
                        case 'juta':
                            $amountCandidate = $num * 1000000;
                            break;
                        case 'm':
                        case 'miliar':
                        case 'miliyar':
                            $amountCandidate = $num * 1000000000;
                            break;
                        default:
                            $amountCandidate = $num;
                    }
                } else {
                    // Without multiplier, dots represent thousands, and commas represent decimals
                    $numStr = str_replace('.', '', $rawNum);
                    $numStr = str_replace(',', '.', $numStr);
                    $amountCandidate = (float) $numStr;
                }

                // Score this match to find the most probable transaction amount
                $score = 0;
                if ($hasRp) $score += 10;
                if ($multiplier) $score += 5;
                if ($amountCandidate >= 100) $score += 2; // Indonesian rupiah mostly > 100

                // Keep the highest scored match
                if ($score > $bestScore) {
                    $bestScore = $score;
                    $amount = $amountCandidate;
                    $bestMatchText = $matchedAmountText;
                }
            }

            // Remove only the selected amount substring from the text
            if ($bestMatchText !== null) {
                $text = preg_replace('/' . preg_quote($bestMatchText, '/') . '/', '', $text, 1);
            }
        }

        // Return null if no valid amount was extracted
        if ($amount === null) {
            return null;
        }

        // 3. Clean up the remaining text to get the description
        // Replace multiple whitespace with single space
        $description = trim(preg_replace('/\s+/', ' ', $text));

        // Remove common trailing/leading noise characters left over
        $description = trim($description, " -:=.,");

        // Set fallback description if string is empty
        if (empty($description)) {
            $description = $type === 'in' ? 'Pemasukan' : 'Pengeluaran';
            $category = $description;
        } else {
            // Capitalize first letter of description
            $description = ucfirst($description);
            // Extract category as the first two words of the description
            $words = explode(' ', $description);
            $category = implode(' ', array_slice($words, 0, 2));
        }

        return [
            'type' => $type,
            'description' => $description,
            'category' => strtolower($category),
            'amount' => $amount,
        ];
    }
}
