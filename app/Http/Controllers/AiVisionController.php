<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiVisionController extends Controller
{
    public function extractInvoice(Request $request)
    {
        try {
            $base64Image =$request->input('image_base64');
            if (!$base64Image) {
                return response()->json(['success' => false, 'error' => 'មិនមានរូបភាពត្រូវបានផ្ញើមកឡើយ']);
            }

            // សំអាត Prefix របស់ Base64 ប្រសិនបើមាន
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {$base64Data = substr($base64Image, strpos($base64Image, ',') + 1);
                $mimeType = 'image/' . strtolower($type[1]);
            } else {
                $base64Data =$base64Image;
                $mimeType = 'image/jpeg';
            }

            $apiKey = env('GEMINI_API_KEY');

            // ប្រើប្រាស់ Model Gemini 3.5 Flash
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent?key={$apiKey}";

            // 💡 Prompt ខ្លីជាងមុន ព្រោះយើងបានប្រើ Schema ខាងក្រោមដើម្បីបញ្ជាវាហើយ
            $prompt = "Extract info from receipt/invoice image. Return ONLY a valid JSON object. Do not add any extra keys, text, or markdown.";

            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'inlineData' => [
                                    'mimeType' => $mimeType,
                                    'data'     => $base64Data
                                ]
                            ]
                        ]
                    ]
                ],
                // 💡 ផ្នែកសំខាន់បំផុត៖ កំណត់ Schema បង្ខំឱ្យ AI ឆ្លើយតបចេញមកតែ Key ទាំង ៥ នេះ ១០០% គ្មានពាក្យលើស
                'generationConfig' => [
                    'responseMimeType' => 'application/json',
                    'responseSchema' => [
                        'type' => 'OBJECT',
                        'properties' => [
                            'customer_name' => ['type' => 'STRING', 'description' => 'Name of the customer or empty string'],
                            'phone' => ['type' => 'STRING', 'description' => 'Phone number or empty string'],
                            'delivery_fee' => ['type' => 'NUMBER', 'description' => 'Delivery fee amount or 0'],
                            'province' => ['type' => 'STRING', 'description' => 'Province name or empty string'],
                            'district' => ['type' => 'STRING', 'description' => 'District name or empty string']
                        ]
                    ]
                ]
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url,$payload);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'error' => $response->json()['error']['message'] ?? 'Gemini API Error'
                ]);
            }

            $resData =$response->json();
            $rawText =$resData['candidates'][0]['content']['parts'][0]['text'] ?? '';

            // 💡 ជំហានទី ១៖ សម្អាត Markdown Tags ចេញឱ្យអស់
            $cleanJsonText = preg_replace('/^```json\s*/i', '', trim($rawText));
            $cleanJsonText = preg_replace('/^```\s*/i', '', $cleanJsonText);$cleanJsonText = preg_replace('/\s*```$/i', '', $cleanJsonText);
            $cleanJsonText = trim($cleanJsonText);

            // បម្លែងទៅជា Array
            $extractedData = json_decode($cleanJsonText, true);

            // 💡 ជំហានទី ២៖ ប្រសិនបើនៅតែ Parse មិនចេញ យើងប្រើ Regex ដើម្បីទាញយកតែ { ... } ដែលនៅខាងដើមគេបំផុត (Lazy match)
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($extractedData)) {
                if (preg_match('/\{[\s\S]*?\}/', $cleanJsonText, $matches)) {
                    $extractedData = json_decode($matches[0], true);
                }
            }

            // 💡 ជំហានទី ៣៖ ការពារចុងក្រោយ បើនៅតែមិនបាន JSON ត្រឹមត្រូវ
            if (!is_array($extractedData)) {
                return response()->json([
                    'success' => false,
                    'error' => 'AI ឆ្លើយតបមិនមែនជា JSON ត្រឹមត្រូវ៖ ' . $rawText
                ]);
            }

            return response()->json([
                'success' => true,
                'data'    => $extractedData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ]);
        }
    }
}
