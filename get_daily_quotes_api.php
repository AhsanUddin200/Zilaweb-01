<?php
function getDailyQuotes() {
    try {
        // Quran API with both Arabic and Urdu translations
        $quran_url = "https://api.alquran.cloud/v1/ayah/random/editions/quran-uthmani,ur.ahmedali";
        $quran_response = file_get_contents($quran_url);
        $quran_data = json_decode($quran_response, true);

        // Get Arabic and Urdu texts
        $quran_arabic = $quran_data['data'][0]['text'] ?? '';
        $quran_urdu = $quran_data['data'][1]['text'] ?? '';

        // Static Hadith in Urdu
        $hadiths = [
            [
                'text' => 'نیت کے بغیر کوئی عمل قبول نہیں ہوتا۔ ہر شخص کو وہی ملے گا جس کی اس نے نیت کی۔',
                'reference' => 'صحیح بخاری: ١'
            ],
            [
                'text' => 'جو شخص اپنے مسلمان بھائی کی ضرورت پوری کرتا ہے اللہ تعالی اس کی ضرورت پوری کرتا ہے۔',
                'reference' => 'صحیح بخاری: ٢٤٢٥'
            ],
        ];

        $random_hadith = $hadiths[array_rand($hadiths)];

        return [
            'quran' => [
                'arabic' => $quran_arabic,
                'urdu' => $quran_urdu,
                'reference' => "سورہ " . ($quran_data['data'][0]['surah']['name'] ?? '') . ": " . ($quran_data['data'][0]['numberInSurah'] ?? '')
            ],
            'hadith' => [
                'text' => $random_hadith['text'],
                'reference' => $random_hadith['reference']
            ]
        ];
    } catch (Exception $e) {
        return [
            'quran' => [
                'arabic' => 'اللَّهُ لَا إِلَٰهَ إِلَّا هُوَ الْحَيُّ الْقَيُّومُ',
                'urdu' => 'اللہ کے سوا کوئی معبود نہیں، وہ زندہ ہے، سب کا تھامنے والا ہے',
                'reference' => 'سورہ البقرة: ٢٥٥'
            ],
            'hadith' => [
                'text' => 'اعمال کا دارومدار نیتوں پر ہے',
                'reference' => 'صحیح بخاری: ١'
            ]
        ];
    }
}
?>