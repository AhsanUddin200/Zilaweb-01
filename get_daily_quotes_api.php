<?php
function getDailyQuotes() {
    // Default quotes in case API fails
    $default_quotes = [
        'quran' => [
            'arabic' => 'إِنَّ اللَّهَ لَا يُغَيِّرُ مَا بِقَوْمٍ حَتَّىٰ يُغَيِّرُوا مَا بِأَنفُسِهِمْ',
            'urdu' => 'بےشک اللہ کسی قوم کی حالت نہیں بدلتا جب تک وہ خود اپنی حالت نہ بدلیں',
            'reference' => 'سورہ الرعد: آیت ١١'
        ],
        'hadith' => [
            'text' => 'اعمال کا دارومدار نیتوں پر ہے اور ہر شخص کے لیے وہی ہے جس کی اس نے نیت کی',
            'reference' => 'صحیح بخاری'
        ]
    ];

    try {
        // Add timeout to prevent long waiting
        $ctx = stream_context_create([
            'http' => [
                'timeout' => 3
            ]
        ]);

        $quran_response = @file_get_contents(
            'https://api.alquran.cloud/v1/ayah/random/editions/quran-uthmani,ur.ahmedali',
            false,
            $ctx
        );

        if ($quran_response !== false) {
            $quran_data = json_decode($quran_response, true);
            
            if ($quran_data && $quran_data['code'] === 200) {
                $default_quotes['quran']['arabic'] = $quran_data['data'][0]['text'];
                $default_quotes['quran']['urdu'] = $quran_data['data'][1]['text'];
                $default_quotes['quran']['reference'] = 'سورہ ' . $quran_data['data'][0]['surah']['name'] . ': آیت ' . $quran_data['data'][0]['numberInSurah'];
            }
        }

        // You can add hadith API integration here if needed

    } catch (Exception $e) {
        // Log error if needed
        error_log('API Error: ' . $e->getMessage());
    }

    return $default_quotes;
}
?>