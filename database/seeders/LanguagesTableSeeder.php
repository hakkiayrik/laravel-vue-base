<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            ["lang_code" => "ach", "name" => "Acholi", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "af", "name" => "Afrikaans", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "an", "name" => "aragonés", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "ar", "name" => "عربي", "is_rtl" => 1, "active" => 0],
            ["lang_code" => "as", "name" => "অসমীয়া", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "ast", "name" => "Asturianu", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "az", "name" => "Azərbaycanca", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "be", "name" => "Беларуская", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "bg", "name" => "Български", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "bn", "name" => "বাংলা", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "br", "name" => "Brezhoneg", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "bs", "name" => "Bosanski", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "ca", "name" => "Català", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "cs", "name" => "Čeština", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "cy", "name" => "Cymraeg", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "da", "name" => "Dansk", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "de", "name" => "Deutsch", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "dsb", "name" => "Dolnoserbšćina", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "el", "name" => "Ελληνικά", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "en", "name" => "English", "is_rtl" => 0, "active" => 1],
            ["lang_code" => "eo", "name" => "Esperanto", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "es", "name" => "Español", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "et", "name" => "Eesti keel", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "eu", "name" => "Euskara", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "fa", "name" => "فارسی", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "ff", "name" => "Pulaar-Fulfulde", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "fi", "name" => "suomi", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "fr", "name" => "Français", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "fy", "name" => "Frysk", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "ga", "name" => "Gaeilge", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "gd", "name" => "Gàidhlig", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "gl", "name" => "Galego", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "gn", "name" => "Avañe'ẽ", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "gu", "name" => "ગુજરાતી (ભારત)", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "he", "name" => "עברית", "is_rtl" => 1, "active" => 0],
            ["lang_code" => "hi", "name" => "हिन्दी (भारत)", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "hr", "name" => "Hrvatski", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "hsb", "name" => "Hornjoserbsce", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "hu", "name" => "magyar", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "hy", "name" => "Հայերեն", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "id", "name" => "Bahasa Indonesia", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "is", "name" => "íslenska", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "it", "name" => "Italiano", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "ja", "name" => "日本語", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "kk", "name" => "Қазақ", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "km", "name" => "ខ្មែរ", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "kn", "name" => "ಕನ್ನಡ", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "ko", "name" => "한국어", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "lij", "name" => "Ligure", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "lt", "name" => "lietuvių kalba", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "lv", "name" => "Latviešu", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "mai", "name" => "मैथिली মৈথিলী", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "mk", "name" => "Македонски", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "ml", "name" => "മലയാളം", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "mr", "name" => "मराठी", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "ms", "name" => "Melayu", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "nb", "name" => "Norsk bokmål", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "nl", "name" => "Nederlands", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "nn", "name" => "Norsk nynorsk", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "or", "name" => "ଓଡ଼ିଆ", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "pa", "name" => "ਪੰਜਾਬੀ (ਭਾਰਤ)", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "pl", "name" => "Polski", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "pt", "name" => "Português", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "rm", "name" => "rumantsch", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "ro", "name" => "Română", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "ru", "name" => "Русский", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "si", "name" => "සිංහල", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "sk", "name" => "slovenčina", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "sl", "name" => "Slovenščina", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "son", "name" => "Soŋay", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "sq", "name" => "Shqip", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "sr", "name" => "Српски", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "sv", "name" => "Svenska", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "ta", "name" => "தமிழ்", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "te", "name" => "తెలుగు", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "th", "name" => "ไทย", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "tr", "name" => "Türkçe", "is_rtl" => 0, "active" => 1],
            ["lang_code" => "uk", "name" => "Українська", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "uz", "name" => "Oʻzbek tili", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "vi", "name" => "Tiếng Việt", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "xh", "name" => "isiXhosa", "is_rtl" => 0, "active" => 0],
            ["lang_code" => "zh", "name" => "中文", "is_rtl" => 0, "active" => 0]
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }
    }

}
