<?php
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title = "How Saran Index Helps in Business – 90 Business Tips for Growth & Local Success";
$meta_description = "Discover how Saran Index empowers local businesses across Chapra and 20 blocks of Saran. Explore the complete 90-Day Online + Offline Business Tips playbook to boost customers, trust, and sales.";
$meta_keywords = "How Saran Index Help in Business, 90 Business Tips Saran, Chapra business growth, Saran district local marketing, grow shop in Bihar, online offline business tips, SaranIndex business directory";
$canonical_url = (defined('BASE_URL') ? BASE_URL : 'https://saranindex.com/') . 'business/';
$langSwitchUrl = 'hindi/business/';

require_once dirname(__DIR__) . '/includes/header.php';

// Complete 90 Business Tips Data
$tips = [
    [
        'id' => 1,
        'type' => 'Online',
        'title' => 'अपने Business को Google पर List करें',
        'subtitle' => 'List Your Business on Google My Business',
        'desc' => 'Google Business Profile बनाकर अपनी दुकान का नाम, सही पता, फोन नंबर और खुलने का समय डालें ताकि स्थानीय ग्राहक आपको Google Search और Maps पर आसानी से ढूंढ सकें।',
        'action' => 'Google Maps पर अपनी दुकान की लोकेशन पिन करें और फोटो अपलोड करें।'
    ],
    [
        'id' => 2,
        'type' => 'Online',
        'title' => 'Saran Index पर अपना Business Register करें',
        'subtitle' => 'Register on SaranIndex.com Directory',
        'desc' => 'सारण जिले की सबसे भरोसेमंद डिजिटल डायरेक्ट्री Saran Index पर अपनी दुकान, क्लिनिक, सर्विस या फर्म को लिस्ट करें ताकि पूरे 20 प्रखंडों के ग्राहक सीधे आपसे संपर्क कर सकें।',
        'action' => 'Saran Index पर Free Listing बनाएं और Verified Trust Badge प्राप्त करें।'
    ],
    [
        'id' => 3,
        'type' => 'Offline',
        'title' => 'दुकान का साफ और आकर्षक Signboard लगाएं',
        'subtitle' => 'Install a Clean & Attractive Signboard',
        'desc' => 'दुकान के मुख्य द्वार पर स्पष्ट, बड़े अक्षरों में दुकान का नाम, मुख्य उत्पाद/सेवाएं और संपर्क नंबर वाला साइनबोर्ड लगाएं जो दूर से और रात में भी आसानी से दिखे।',
        'action' => 'बोर्ड पर मुख्य सर्विस और मोबाइल नंबर जरूर लिखें।'
    ],
    [
        'id' => 4,
        'type' => 'Online',
        'title' => 'WhatsApp Business Profile बनाएं',
        'subtitle' => 'Set Up a Professional WhatsApp Business Profile',
        'desc' => 'साधारण WhatsApp की जगह WhatsApp Business ऐप इस्तेमाल करें। इसमें दुकान का नाम, पता, समय, ईमेल, वेबसाइट और कैटलॉग जोड़कर पेशेवर पहचान बनाएं।',
        'action' => 'Auto-reply (Away message और Greeting message) चालू करें।'
    ],
    [
        'id' => 5,
        'type' => 'Offline',
        'title' => 'दुकान की साफ-सफाई पर ध्यान दें',
        'subtitle' => 'Maintain Impeccable Store Hygiene & Cleanliness',
        'desc' => 'ग्राहक सबसे पहले दुकान की सफाई और सुव्यवस्था देखता है। काउंटर, फर्श और डिस्प्ले को हमेशा चमकता और धूल-मुक्त रखें।',
        'action' => 'रोज सुबह दुकान खुलने से पहले पूरी साफ-सफाई सुनिश्चित करें।'
    ],
    [
        'id' => 6,
        'type' => 'Online',
        'title' => 'Business की सही Location Online डालें',
        'subtitle' => 'Ensure Accurate Geo-Location Across Platforms',
        'desc' => 'Google Maps, Saran Index और सोशल मीडिया पर अपनी सटीक जीपीएस लोकेशन डालें ताकि दूर से आने वाले ग्राहक बिना भटके आपकी दुकान तक पहुंच सकें।',
        'action' => 'दुकान के सामने खड़े होकर मैप पर लोकेशन पिन वेरीफाई करें।'
    ],
    [
        'id' => 7,
        'type' => 'Offline',
        'title' => 'Customer का स्वागत मुस्कान के साथ करें',
        'subtitle' => 'Greet Every Customer with a Warm Smile',
        'desc' => 'दुकान में प्रवेश करते ही ग्राहक का स्वागत "नमस्ते/प्रणाम" और मुस्कान के साथ करें। यह सकारात्मक माहौल बनाता है और ग्राहक को विशेष महसूस कराता है।',
        'action' => 'ग्राहक से आँख मिलाकर शालीनता से पूछें - "बताइए, हम आपकी क्या मदद कर सकते हैं?"'
    ],
    [
        'id' => 8,
        'type' => 'Online',
        'title' => 'Business की अच्छी Photos Upload करें',
        'subtitle' => 'Upload High-Quality Real Business Photos',
        'desc' => 'अपनी दुकान के सामने का दृश्य, अंदर का डिस्प्ले, प्रोडक्ट्स और स्टाफ की साफ और अच्छी रोशनी वाली तस्वीरें अपनी ऑनलाइन प्रोफाइल पर अपलोड करें।',
        'action' => 'धुंधली तस्वीरों से बचें; दिन की अच्छी रोशनी में रियल फोटो खींचें।'
    ],
    [
        'id' => 9,
        'type' => 'Offline',
        'title' => 'Products को व्यवस्थित तरीके से Display करें',
        'subtitle' => 'Organize & Display Products Systematically',
        'desc' => 'सामान को श्रेणीवार और नजर के स्तर (Eye Level) पर रखें। सबसे ज्यादा बिकने वाले या नए प्रोडक्ट्स को आगे रखें ताकि ग्राहक की नजर तुरंत पड़े।',
        'action' => 'संबंधित सामान एक साथ रखें (जैसे टूथपेस्ट के पास ब्रश)।'
    ],
    [
        'id' => 10,
        'type' => 'Online',
        'title' => 'अपना Business Timing Online रखें',
        'subtitle' => 'Publish Accurate Working Hours Online',
        'desc' => 'दुकान खुलने और बंद होने का सही समय और साप्ताहिक अवकाश ऑनलाइन अपडेट रखें ताकि ग्राहक बेवजह चक्कर लगाने से बचें।',
        'action' => 'त्योहारों या छुट्टियों में समय में बदलाव हो तो ऑनलाइन तुरंत अपडेट करें।'
    ],
    [
        'id' => 11,
        'type' => 'Offline',
        'title' => 'दुकान के बाहर Price/Offer Board लगाएं',
        'subtitle' => 'Display Offers & Popular Prices Outside the Store',
        'desc' => 'दुकान के बाहर एक छोटा आकर्षक ब्लैकबोर्ड या स्टैंडी लगाएं जिस पर आज का खास ऑफर, डिस्काउंट या लोकप्रिय आइटम की कीमत लिखी हो।',
        'action' => 'रास्ते से गुजरने वाले ग्राहकों का ध्यान खींचने के लिए नियमित ऑफर बदलें।'
    ],
    [
        'id' => 12,
        'type' => 'Online',
        'title' => 'WhatsApp Catalogue बनाएं',
        'subtitle' => 'Build a Digital WhatsApp Product Catalogue',
        'desc' => 'WhatsApp Business में अपने सभी मुख्य प्रोडक्ट्स या सेवाओं का कैटलॉग बनाएं जिसमें फोटो, विवरण और कीमत शामिल हो। ग्राहक को आसानी से शेयर करें।',
        'action' => 'ग्राहक द्वारा सामान पूछने पर सीधा कैटलॉग लिंक भेजें।'
    ],
    [
        'id' => 13,
        'type' => 'Offline',
        'title' => 'ग्राहकों से विनम्रता से बात करें',
        'subtitle' => 'Communicate with Utmost Politeness & Patience',
        'desc' => 'भीड़ या दबाव में भी अपना स्वभाव शांत रखें। विनम्र भाषा और धैर्य ग्राहक का दिल जीत लेता है और वे बार-बार आपकी दुकान पर आते हैं।',
        'action' => '"धन्यवाद", "कृपया", "फिर पधारें" जैसे शब्दों का सहज प्रयोग करें।'
    ],
    [
        'id' => 14,
        'type' => 'Online',
        'title' => 'Facebook Business Page बनाएं',
        'subtitle' => 'Create & Optimize a Facebook Business Page',
        'desc' => 'अपने नाम से अलग बिज़नेस के नाम से फेसबुक पेज बनाएं। स्थानीय ग्राहकों से जुड़ने, नए ऑफर्स साझा करने और दुकान की जानकारी देने के लिए यह बेहद असरदार है।',
        'action' => 'पेज पर दुकान का फोन, पता और Saran Index प्रोफाइल लिंक जोड़ें।'
    ],
    [
        'id' => 15,
        'type' => 'Offline',
        'title' => 'Visiting Card जरूर रखें',
        'subtitle' => 'Keep Professional Printed Visiting Cards Handy',
        'desc' => 'हमेशा काउंटर पर और अपनी जेब में साफ-सुथरा विजिटिंग कार्ड रखें। हर नए ग्राहक या व्यापारी को कार्ड दें ताकि जरूरत पड़ने पर वे तुरंत कॉल कर सकें।',
        'action' => 'कार्ड पर QR कोड छपवाएं जिसे स्कैन करके ग्राहक आपका फोन नंबर या Saran Index प्रोफाइल देख सकें।'
    ],
    [
        'id' => 16,
        'type' => 'Online',
        'title' => 'Instagram पर Business Profile बनाएं',
        'subtitle' => 'Launch an Engaging Instagram Business Account',
        'desc' => 'युवा ग्राहकों को आकर्षित करने के लिए इंस्टाग्राम पर बिजनेस अकाउंट बनाएं। नए स्टॉक, ट्रेंडिंग प्रोडक्ट्स और कस्टमर की खुशियों की तस्वीरें पोस्ट करें।',
        'action' => 'बायो में Chapra / Saran का लोकेशन टैग और WhatsApp लिंक लगाएं।'
    ],
    [
        'id' => 17,
        'type' => 'Offline',
        'title' => 'नियमित Customers को पहचानें और महत्व दें',
        'subtitle' => 'Recognize & Value Loyal Returning Customers',
        'desc' => 'रेगुलर ग्राहकों का नाम याद रखें और उनकी पसंद-नापसंद का ध्यान रखें। उन्हें विशेष छूट या प्राथमिकता देकर अपनापन महसूस कराएं।',
        'action' => 'दुकान में आते ही उन्हें नाम से संबोधित करें।'
    ],
    [
        'id' => 18,
        'type' => 'Online',
        'title' => 'अपने Products की Short Videos बनाएं',
        'subtitle' => 'Create Engaging 30-Second Short Videos & Reels',
        'desc' => 'नए सामान की अनबॉक्सिंग, इस्तेमाल करने का तरीका या क्वालिटी दिखाते हुए 30 सेकंड की छोटी वीडियो बनाकर WhatsApp Status और Instagram Reels पर डालें।',
        'action' => 'वीडियो में साफ आवाज और प्रोडक्ट की खासियत संक्षेप में बताएं।'
    ],
    [
        'id' => 19,
        'type' => 'Offline',
        'title' => 'दुकान की Lighting अच्छी रखें',
        'subtitle' => 'Ensure Bright & Warm In-Store Lighting',
        'desc' => 'अंधेरी दुकान ग्राहकों को आकर्षित नहीं करती। काउंटर और डिस्प्ले रैक पर अच्छी ब्राइट LED लाइट्स लगाएं जिससे सामान आकर्षक और चमकदार दिखे।',
        'action' => 'दुकान के प्रवेश द्वार और मुख्य डिस्प्ले पर फोकस लाइट लगाएं।'
    ],
    [
        'id' => 20,
        'type' => 'Online',
        'title' => 'Customer Reviews प्राप्त करें',
        'subtitle' => 'Actively Collect Positive Online Customer Reviews',
        'desc' => 'संतुष्ट ग्राहकों से Google और Saran Index पर 5-स्टार रेटिंग और अच्छे शब्द लिखने का विनम्र अनुरोध करें। पॉजिटिव रिव्यू नए ग्राहकों का भरोसा बढ़ाते हैं।',
        'action' => 'बिलिंग के समय QR कोड दिखाकर 30 सेकंड में रिव्यू देने को कहें।'
    ],
    [
        'id' => 21,
        'type' => 'Offline',
        'title' => 'शिकायतों को ध्यान से सुनें',
        'subtitle' => 'Listen Actively & Empathize with Complaints',
        'desc' => 'यदि कोई ग्राहक किसी सामान से असंतुष्ट है, तो बहस करने के बजाय धैर्य से पूरी बात सुनें और तुरंत उचित समाधान (बदलाव या रिफंड) दें।',
        'action' => 'शिकायत दूर करने के बाद ग्राहक का भरोसा और ज्यादा मजबूत हो जाता है।'
    ],
    [
        'id' => 22,
        'type' => 'Online',
        'title' => 'Reviews का Reply करें',
        'subtitle' => 'Promptly Respond to Every Online Review',
        'desc' => 'चाहे अच्छा रिव्यू हो या कोई सुझाव, हर रिव्यू का शालीनता से जवाब दें। अच्छे रिव्यू पर धन्यवाद कहें और आलोचना पर सुधार का आश्वासन दें।',
        'action' => 'यह दर्शाता है कि आप ग्राहकों की राय का पूरा सम्मान करते हैं।'
    ],
    [
        'id' => 23,
        'type' => 'Offline',
        'title' => 'Customer को सही जानकारी दें',
        'subtitle' => 'Always Provide Honest & Transparent Product Info',
        'desc' => 'सिर्फ सामान बेचने के लिए कभी गलत दावा न करें। एक्सपायरी डेट, वारंटी और सही उपयोग की ईमानदार जानकारी दें। ईमानदारी ही लंबी सफलता की नींव है।',
        'action' => 'अगर कोई सामान ग्राहक की जरूरत का नहीं है, तो उसे सही सलाह दें।'
    ],
    [
        'id' => 24,
        'type' => 'Online',
        'title' => 'Local Keywords का इस्तेमाल करें',
        'subtitle' => 'Incorporate Hyper-Local Area Keywords',
        'desc' => 'अपनी ऑनलाइन पोस्ट और प्रोफाइल में अपने शहर और प्रखंड का नाम (जैसे "Chapra", "Marhaura", "Sonpur", "Saran") जरूर शामिल करें ताकि स्थानीय सर्च में आप ऊपर दिखें।',
        'action' => 'जैसे: "Best Electrical Shop in Chapra Saran" आदि कीवर्ड्स का प्रयोग करें।'
    ],
    [
        'id' => 25,
        'type' => 'Offline',
        'title' => 'आसपास के Businesses से संपर्क बढ़ाएं',
        'subtitle' => 'Network & Collaborate with Neighborhood Businesses',
        'desc' => 'अपने मार्केट के अन्य दुकानदारों और व्यापारियों से अच्छे संबंध बनाएं। वे आपकी दुकान पर ऐसे ग्राहक भेज सकते हैं जिनका सामान उनके पास उपलब्ध न हो।',
        'action' => 'एक-दूसरे के बिजनेस को प्रमोट करने का आपसी समझ बनाएं।'
    ],
    [
        'id' => 26,
        'type' => 'Online',
        'title' => 'अपना Mobile Number हर Platform पर समान रखें',
        'subtitle' => 'Maintain Consistent NAP (Name, Address, Phone)',
        'desc' => 'Google, Saran Index, Facebook, WhatsApp और विजिटिंग कार्ड पर एक ही मुख्य कॉलिंग/WhatsApp नंबर रखें ताकि ग्राहकों में कोई भ्रम न रहे।',
        'action' => 'एक आधिकारिक बिज़नेस मोबाइल नंबर तय करें जो हमेशा चालू रहे।'
    ],
    [
        'id' => 27,
        'type' => 'Offline',
        'title' => 'कर्मचारियों को Customer Service की Training दें',
        'subtitle' => 'Train Your Staff in Courteous Customer Service',
        'desc' => 'दुकान के सहायकों को सिखाएं कि ग्राहकों से कैसे आदर से बात करनी है, सामान कैसे दिखाना है और किसी भी स्थिति में गुस्सा नहीं करना है।',
        'action' => 'स्टाफ का विनम्र व्यवहार आपकी दुकान की प्रतिष्ठा बढ़ाता है।'
    ],
    [
        'id' => 28,
        'type' => 'Online',
        'title' => 'UPI QR Code की जानकारी Online रखें',
        'subtitle' => 'Mention Online Payment & QR Options',
        'desc' => 'अपनी प्रोफाइल पर यह जरूर बताएं कि आप Google Pay, PhonePe, Paytm आदि डिजिटल पेमेंट स्वीकार करते हैं। इससे ऑनलाइन ऑर्डर देने वाले ग्राहकों को सुविधा होती है।',
        'action' => 'सोशल मीडिया और कैटलॉग पर डिजिटल पेमेंट की उपलब्धता हाइलाइट करें।'
    ],
    [
        'id' => 29,
        'type' => 'Offline',
        'title' => 'दुकान में UPI Payment की सुविधा रखें',
        'subtitle' => 'Display Visible UPI QR Standees at Billing Counter',
        'desc' => 'काउंटर पर स्पष्ट दिखने वाला QR कोड स्टैंडी लगाएं और साउंडबॉक्स की सुविधा रखें ताकि छुट्टे पैसे की समस्या खत्म हो और पेमेंट तुरंत हो सके।',
        'action' => 'QR कोड को साफ रखें और इंटरनेट कनेक्टिविटी दुरुस्त रखें।'
    ],
    [
        'id' => 30,
        'type' => 'Online',
        'title' => 'Festival Offers Social Media पर डालें',
        'subtitle' => 'Announce Festive Offers & Deals Online',
        'desc' => 'होली, छठ, दिवाली, दुर्गा पूजा, ईद या नए साल पर विशेष ऑफर पोस्टर बनाकर WhatsApp Status, Facebook और Instagram पर साझा करें।',
        'action' => 'त्योहार से 7-10 दिन पहले ऑफर्स का प्रचार शुरू कर दें।'
    ],
    [
        'id' => 31,
        'type' => 'Offline',
        'title' => 'त्योहारों के अनुसार दुकान सजाएं',
        'subtitle' => 'Theme & Decorate Store for Major Festivals',
        'desc' => 'त्योहारों के अवसर पर दुकान में रोशनी, फूलों या गुब्बारों से सजावट करें और त्योहारी थीम वाले प्रोडक्ट्स को सबसे आगे सजाएं।',
        'action' => 'त्योहारी माहौल ग्राहकों को खरीदारी करने के लिए प्रेरित करता है।'
    ],
    [
        'id' => 32,
        'type' => 'Online',
        'title' => 'WhatsApp Status पर रोज Business Update दें',
        'subtitle' => 'Post Daily Engaging Business Updates on Status',
        'desc' => 'रोज सुबह या दोपहर में 1-2 अच्छे प्रोडक्ट्स, उपयोगी जानकारी या स्पेशल डिस्काउंट का स्टेटस लगाएं। लगातार मौजूदगी से ग्राहक आपको याद रखते हैं।',
        'action' => 'बहुत ज्यादा स्पैम न करें; दिन में 2 से 3 बेहतरीन स्टेटस ही काफी हैं।'
    ],
    [
        'id' => 33,
        'type' => 'Offline',
        'title' => 'पुराने Customers से संपर्क बनाए रखें',
        'subtitle' => 'Nurture Long-Term Relationships with Past Buyers',
        'desc' => 'जो ग्राहक कुछ समय से दुकान पर नहीं आए, उनसे त्योहारों पर हालचाल पूछें या नए कलेक्शन की जानकारी देकर उनके साथ रिश्ता बनाए रखें।',
        'action' => 'कॉल या मैसेज करके कहें - "सर, आपके पसंद का नया स्टॉक आया है।"'
    ],
    [
        'id' => 34,
        'type' => 'Online',
        'title' => 'Product/Service की कीमत स्पष्ट करें',
        'subtitle' => 'Maintain Transparent & Honest Pricing Online',
        'desc' => 'ऑनलाइन कैटलॉग और पोस्ट्स में स्पष्ट कीमत या शुरुआती रेंज लिखें। पारदर्शी मूल्य निर्धारण से ग्राहकों का भरोसा तेजी से बढ़ता है।',
        'action' => 'छिपे हुए चार्ज न रखें; स्पष्टता ग्राहक का विश्वास जीतती है।'
    ],
    [
        'id' => 35,
        'type' => 'Offline',
        'title' => 'नकद और Digital दोनों Payment रखें',
        'subtitle' => 'Support Both Cash & Digital Payment Modes Seamlessly',
        'desc' => 'ग्रामीण और शहरी दोनों ग्राहकों की सुविधा के लिए नकद के साथ-साथ QR, कार्ड और UPI की पूरी व्यवस्था रखें ताकि कोई ग्राहक पेमेंट के कारण न लौटे।',
        'action' => 'काउंटर पर पर्याप्त छुट्टे पैसे की व्यवस्था रखें।'
    ],
    [
        'id' => 36,
        'type' => 'Online',
        'title' => 'एक Professional Business Email बनाएं',
        'subtitle' => 'Set Up a Clean, Professional Business Email',
        'desc' => 'व्यक्तिगत ईमेल की जगह व्यापार के नाम से ईमेल बनाएं (जैसे: yourbusiness.chapra@gmail.com या info@yourbusiness.com)। यह पेशेवर छवि देता है।',
        'action' => 'सप्लायर्स और आधिकारिक पत्राचार में इसी ईमेल का उपयोग करें।'
    ],
    [
        'id' => 37,
        'type' => 'Offline',
        'title' => 'Bill/Receipt जरूर दें',
        'subtitle' => 'Provide Printed or Digital Bills to Every Customer',
        'desc' => 'हर खरीद पर ग्राहक को पक्का या डिजिटल बिल दें। बिल पर दुकान का नाम, फोन नंबर, पता और रिटर्न पॉलिसी स्पष्ट रूप से छपी होनी चाहिए।',
        'action' => 'बिल पर Saran Index का प्रोफाइल लिंक या QR कोड भी जोड़ सकते हैं।'
    ],
    [
        'id' => 38,
        'type' => 'Online',
        'title' => 'अपनी Website बनाएं',
        'subtitle' => 'Build or Showcase Your Business Web Presence',
        'desc' => 'अपने बिज़नेस की एक आधुनिक वेबसाइट बनाएं या Saran Index पर अपना डेडिकेटेड वेब पेज बनाएं जिसे आप दुनिया में कहीं भी साझा कर सकें।',
        'action' => 'वेबसाइट से आपके बिज़नेस की विश्वसनीयता कई गुना बढ़ जाती है।'
    ],
    [
        'id' => 39,
        'type' => 'Offline',
        'title' => 'दुकान के आसपास साफ-सफाई रखें',
        'subtitle' => 'Keep the Store Surroundings Clean & Welcoming',
        'desc' => 'दुकान के सामने का रास्ता, नाली और आसपास का क्षेत्र साफ रखें। एक डस्टबिन बाहर रखें ताकि कचरा न फैले और ग्राहक खुशी से आ सकें।',
        'action' => 'साफ सुथरा प्रवेश द्वार ग्राहकों पर बेहतरीन प्रभाव डालता है।'
    ],
    [
        'id' => 40,
        'type' => 'Online',
        'title' => 'Website पर Contact और Location दें',
        'subtitle' => 'Highlight Direct Contact & Click-to-Call Buttons',
        'desc' => 'वेबसाइट या प्रोफाइल पर सबसे ऊपर "Call Now", "WhatsApp Us" और "Get Directions" के बटन रखें ताकि ग्राहक को संपर्क करने में एक सेकंड भी न लगे।',
        'action' => 'मोबाइल फ्रेंडली बटन्स से कन्वर्जन 50% तक बढ़ जाता है।'
    ],
    [
        'id' => 41,
        'type' => 'Offline',
        'title' => 'Customer के लिए बैठने की उचित व्यवस्था करें',
        'subtitle' => 'Provide Comfortable Seating for Visiting Customers',
        'desc' => 'बुजुर्गों, महिलाओं और साथ आए बच्चों के लिए आरामदायक कुर्सियां या बेंच रखें। गर्मियों में पीने का ठंडा पानी और पंखे की व्यवस्था जरूर रखें।',
        'action' => 'संतुष्ट और आरामदायक महसूस करने वाला ग्राहक ज्यादा समय बिताता है।'
    ],
    [
        'id' => 42,
        'type' => 'Online',
        'title' => 'नियमित Social Media Posting करें',
        'subtitle' => 'Post Consistently on Social Media (3-4 Times/Week)',
        'desc' => 'हफ्ते में कम से कम 3-4 बार उपयोगी पोस्ट, प्रोडक्ट फोटो या ग्राहकों के अनुभव शेयर करें। नियमितता से सोशल मीडिया का एल्गोरिदम आपकी पहुंच बढ़ाता है।',
        'action' => 'एक फिक्स समय तय करें (जैसे शाम 6 से 8 बजे जब लोग फोन देखते हैं)।'
    ],
    [
        'id' => 43,
        'type' => 'Offline',
        'title' => 'Local Events में Business की भागीदारी करें',
        'subtitle' => 'Participate in Local Melas, Festivals & Community Events',
        'desc' => 'सारण के स्थानीय मेलों (जैसे सोनपुर मेला), खेल प्रतियोगिताओं, पूजा पंडालों या स्कूल कार्यक्रमों में छोटा बैनर लगाएं या स्पॉन्सरशिप दें।',
        'action' => 'स्थानीय समाज में आपकी दुकान का नाम तेजी से फैलता है।'
    ],
    [
        'id' => 44,
        'type' => 'Online',
        'title' => 'Educational/Useful Content भी Share करें',
        'subtitle' => 'Share Informative, Useful Tips Beyond Just Selling',
        'desc' => 'सिर्फ सामान बेचने की पोस्ट न करें। अपने क्षेत्र से जुड़े उपयोगी टिप्स भी साझा करें (जैसे कपड़े की देखभाल कैसे करें, दवाओं का सही उपयोग, घर की वायरिंग आदि)।',
        'action' => 'लोग उपयोगी जानकारी देने वाले दुकानदार को एक्सपर्ट और भरोसेमंद मानते हैं।'
    ],
    [
        'id' => 45,
        'type' => 'Offline',
        'title' => 'Referral से नए Customers लाएं',
        'subtitle' => 'Leverage Word-of-Mouth & Customer Referrals',
        'desc' => 'संतुष्ट ग्राहकों से कहें कि यदि उन्हें सर्विस अच्छी लगी तो वे अपने दोस्तों और रिश्तेदारों को भी आपकी दुकान के बारे में जरूर बताएं।',
        'action' => '"माउथ पब्लिसिटी" स्थानीय व्यापार का सबसे शक्तिशाली हथियार है।'
    ],
    [
        'id' => 46,
        'type' => 'Online',
        'title' => 'Customer Database व्यवस्थित रखें',
        'subtitle' => 'Maintain an Organized Digital Customer Database',
        'desc' => 'ग्राहकों के नाम और मोबाइल नंबर डिजिटल रूप से सुरक्षित रखें। इससे नए ऑफर्स, जन्मदिन की शुभकामनाएं और जरूरी अपडेट्स भेजना आसान हो जाता है।',
        'action' => 'Google Contacts या Excel/CRM में ग्राहकों के नंबर व्यवस्थित करें।'
    ],
    [
        'id' => 47,
        'type' => 'Offline',
        'title' => 'Referral के लिए छोटा Incentive दें',
        'subtitle' => 'Reward Customers Who Refer New Clients',
        'desc' => 'जब कोई पुराना ग्राहक किसी नए व्यक्ति को आपके पास भेजे, तो अगली खरीद पर पुराने ग्राहक को 5% अतिरिक्त डिस्काउंट या कोई छोटा उपहार दें।',
        'action' => 'यह ग्राहकों को आपका ब्रांड एंबेसडर बना देता है।'
    ],
    [
        'id' => 48,
        'type' => 'Online',
        'title' => 'पुराने Customers को WhatsApp Updates भेजें',
        'subtitle' => 'Send Personalized WhatsApp Broadcasts to Loyal Buyers',
        'desc' => 'WhatsApp Broadcast लिस्ट बनाकर पुराने ग्राहकों को महीने में 1-2 बार नए आगमन और एक्सक्लूसिव डिस्काउंट की जानकारी भेजें।',
        'action' => 'स्पैम न करें; केवल उपयोगी और वैल्यू देने वाले मैसेज भेजें।'
    ],
    [
        'id' => 49,
        'type' => 'Offline',
        'title' => 'Nearby दुकानदारों से Partnership करें',
        'subtitle' => 'Form Cross-Promotional Tie-ups with Nearby Stores',
        'desc' => 'जैसे कपड़े की दुकान वाले टेलर या जूतों की दुकान से टाई-अप कर सकते हैं। एक-दूसरे के कूपन बांटकर दोनों का ग्राहक दायरा बढ़ाएं।',
        'action' => 'पारस्परिक सहयोग से दोनों व्यवसायों की बिक्री बढ़ती है।'
    ],
    [
        'id' => 50,
        'type' => 'Online',
        'title' => 'Online Offers और Coupons बनाएं',
        'subtitle' => 'Create Exclusive Online Promo Codes & Coupons',
        'desc' => 'सोशल मीडिया पर कूपन कोड शेयर करें (जैसे "CHAPRA10" दिखाकर 10% छूट पाएं)। इससे ऑनलाइन पोस्ट का सीधा असर दुकान की बिक्री पर दिखता है।',
        'action' => 'कूपन की एक्सपायरी डेट सीमित रखें ताकि ग्राहक जल्दी आएं।'
    ],
    [
        'id' => 51,
        'type' => 'Offline',
        'title' => 'Combo Offers तैयार करें',
        'subtitle' => 'Package Complementary Products into Combo Deals',
        'desc' => '2-3 संबंधित सामानों का कॉम्बो पैक बनाएं और अलग-अलग खरीदने की तुलना में थोड़ा कम दाम रखें। ग्राहक कॉम्बो ऑफर की ओर तुरंत आकर्षित होते हैं।',
        'action' => 'उदाहरण: "शर्ट + पैंट + बेल्ट कॉम्बो" या "किराना मासिक राशन कॉम्बो"।'
    ],
    [
        'id' => 52,
        'type' => 'Online',
        'title' => 'Product की Before/After Photos दिखाएं',
        'subtitle' => 'Showcase Transformational Before/After Results',
        'desc' => 'सर्विस बिजनेस (सैलून, डेंटिंग-पेंटिंग, रिपेयरिंग, इंटीरियर, टेलर) में काम से पहले और काम के बाद की फोटो साझा करें। यह आपके काम की गुणवत्ता का सबसे बड़ा सबूत है।',
        'action' => 'ग्राहक की अनुमति लेकर ही तस्वीरें साझा करें।'
    ],
    [
        'id' => 53,
        'type' => 'Offline',
        'title' => 'Fast-moving Products का पर्याप्त Stock रखें',
        'subtitle' => 'Keep High-Demand Fast-Moving Items in Stock',
        'desc' => 'जो सामान सबसे ज्यादा बिकता है, उसका स्टॉक कभी खत्म न होने दें। ग्राहक को "सामान नहीं है" कहकर खाली हाथ वापस भेजना बिजनेस के लिए नुकसानदेह है।',
        'action' => 'हफ्ते के अंत में स्टॉक की समीक्षा करें और पहले से ऑर्डर दें।'
    ],
    [
        'id' => 54,
        'type' => 'Online',
        'title' => 'अपने Best-selling Products Highlight करें',
        'subtitle' => 'Highlight Top-Selling Products Online',
        'desc' => 'सोशल मीडिया और Saran Index प्रोफाइल पर अपने सबसे लोकप्रिय और बेस्टसेलर सामान को "Most Popular" टैग के साथ प्रमुखता से दिखाएं।',
        'action' => 'नए ग्राहकों को क्या खरीदना चाहिए, यह निर्णय लेने में मदद मिलती है।'
    ],
    [
        'id' => 55,
        'type' => 'Offline',
        'title' => 'Slow-moving Stock की पहचान करें',
        'subtitle' => 'Identify & Clear Slow-Moving Inventory',
        'desc' => 'जो सामान लंबे समय से नहीं बिका, उसे पहचानें। उस पर स्पेशल डिस्काउंट या क्लीयरेंस सेल लगाकर पूंजी को जल्दी खाली करें।',
        'action' => 'फंसी हुई पूंजी को तेजी से बिकने वाले नए सामान में लगाएं।'
    ],
    [
        'id' => 56,
        'type' => 'Online',
        'title' => 'Competitors की Online Presence देखें',
        'subtitle' => 'Monitor Competitors Online Activity & Offers',
        'desc' => 'देखें कि आपके क्षेत्र या शहर के अन्य व्यापारी ऑनलाइन क्या पोस्ट कर रहे हैं, क्या नया ला रहे हैं और ग्राहक उन पर क्या प्रतिक्रिया दे रहे हैं।',
        'action' => 'नकल न करें, बल्कि उनसे बेहतर और अनोखी सर्विस देने की योजना बनाएं।'
    ],
    [
        'id' => 57,
        'type' => 'Offline',
        'title' => 'Competitors की कीमत और Service समझें',
        'subtitle' => 'Understand Market Pricing & Service Benchmarks',
        'desc' => 'मार्केट में चल रही कीमतों और प्रतिस्पर्धियों के व्यवहार को समझें। अपनी कीमत उचित रखें और सर्विस की गुणवत्ता को हमेशा एक कदम आगे रखें।',
        'action' => 'सिर्फ सस्ते होने से नहीं, बेहतर व्यवहार और सर्विस से मुकाबला जीतें।'
    ],
    [
        'id' => 58,
        'type' => 'Online',
        'title' => 'Customer Questions का FAQ बनाएं',
        'subtitle' => 'Build a FAQ Section Addressing Common Questions',
        'desc' => 'ग्राहक अक्सर जो सवाल पूछते हैं (जैसे डिलीवरी चार्ज, रिटर्न नियम, टाइमिंग, होम सर्विस), उनके स्पष्ट उत्तर अपनी वेबसाइट या सोशल मीडिया पर लिख दें।',
        'action' => 'बार-बार एक ही सवाल का जवाब देने का समय बचता है।'
    ],
    [
        'id' => 59,
        'type' => 'Offline',
        'title' => 'Customer के सवालों का स्पष्ट जवाब दें',
        'subtitle' => 'Answer In-Store Queries with Clarity & Honesty',
        'desc' => 'ग्राहक जब दुकान में कोई सवाल पूछे तो गोल-मोल जवाब देने के बजाय साफ और सटीक जानकारी दें। पारदर्शिता से रिश्ते मजबूत होते हैं।',
        'action' => 'यदि किसी सवाल का जवाब तुरंत न पता हो तो विनम्रता से पता करके बताएं।'
    ],
    [
        'id' => 60,
        'type' => 'Online',
        'title' => 'Video Testimonials Share करें',
        'subtitle' => 'Record & Share Short Video Testimonials',
        'desc' => 'खुश ग्राहक का 20 सेकंड का छोटा वीडियो फीडबैक रिकॉर्ड करें जिसमें वे आपकी दुकान और सेवा की तारीफ करें। यह नए ग्राहकों में 100% भरोसा पैदा करता है।',
        'action' => 'वीडियो को WhatsApp Status और Facebook पर शेयर करें।'
    ],
    [
        'id' => 61,
        'type' => 'Offline',
        'title' => 'अच्छे Customer Experience पर ध्यान दें',
        'subtitle' => 'Deliver an Unforgettable In-Store Experience',
        'desc' => 'दुकान में आने से लेकर जाने तक का पूरा अनुभव सुखद बनाएं। सामान पैक करने की अच्छी क्वालिटी, कैरी बैग और तेजी से बिलिंग पर ध्यान दें।',
        'action' => 'एक अच्छा अनुभव ग्राहक को जिंदगी भर का वफादार बना देता है।'
    ],
    [
        'id' => 62,
        'type' => 'Online',
        'title' => 'Local Area के नाम से Content बनाएं',
        'subtitle' => 'Create Content Referencing Local Landmarks & Areas',
        'desc' => 'अपनी पोस्ट में स्थानीय जगहों का नाम लिखें (जैसे "दरोगा राय चौक", "नगर पालिका चौक", "थाना रोड", "गुदरी बाजार")। स्थानीय लोग इससे तुरंत कनेक्ट होते हैं।',
        'action' => '"छपरा शहर में सबसे बेहतरीन..." जैसे वाक्यों का प्रयोग करें।'
    ],
    [
        'id' => 63,
        'type' => 'Offline',
        'title' => 'अपने क्षेत्र के Customers को Target करें',
        'subtitle' => 'Focus Marketing Within Your Immediate 5-10 KM Radius',
        'desc' => 'अपनी दुकान के 5-10 किलोमीटर के दायरे के निवासियों की जरूरतों को समझें। वहां के रहन-सहन और पसंद के अनुसार ही माल स्टॉक करें।',
        'action' => 'स्थानीय प्राथमिकताओं के अनुसार उत्पाद चयन करें।'
    ],
    [
        'id' => 64,
        'type' => 'Online',
        'title' => 'Online Enquiry का जल्दी Reply करें',
        'subtitle' => 'Respond to Online Inquiries Within 5-15 Minutes',
        'desc' => 'WhatsApp, Facebook या Saran Index से आई किसी भी पूछताछ का 5 से 15 मिनट के अंदर उत्तर दें। देर करने से ग्राहक दूसरे दुकानदार के पास चला जाता है।',
        'action' => 'त्वरित उत्तर को प्राथमिकता दें; स्पीड ही बिक्री बढ़ाती है।'
    ],
    [
        'id' => 65,
        'type' => 'Offline',
        'title' => 'Phone Call का समय पर जवाब दें',
        'subtitle' => 'Answer Customer Phone Calls Promptly & Professionally',
        'desc' => 'दुकान का फोन बजते ही 2-3 रिंग में उठाएं। यदि किसी कारण से कॉल छूट जाए तो जैसे ही फुर्सत मिले तुरंत कॉल बैक करें।',
        'action' => 'कॉल पर हमेशा पेशेवर तरीके से दुकान का नाम लेकर बात शुरू करें।'
    ],
    [
        'id' => 66,
        'type' => 'Online',
        'title' => 'Chat/WhatsApp में Auto Reply लगाएं',
        'subtitle' => 'Configure Instant Auto-Replies for Off-Hours',
        'desc' => 'रात में या दुकान बंद होने के समय WhatsApp पर ऑटो-रिप्लाई सेट करें: "नमस्ते! हमारी दुकान सुबह 9 बजे खुलेगी। हम जल्द ही आपसे संपर्क करेंगे।"',
        'action' => 'ग्राहक को यह पता चलता है कि उसका मैसेज पहुंच गया है।'
    ],
    [
        'id' => 67,
        'type' => 'Offline',
        'title' => 'Delivery Service उपलब्ध कराएं',
        'subtitle' => 'Provide Fast Local Delivery for Convenience',
        'desc' => 'स्थानीय ग्राहकों के लिए होम डिलीवरी की सुविधा शुरू करें। व्यस्त लोगों, बुजुर्गों और गृहिणियों के लिए यह बेहद मददगार साबित होती है।',
        'action' => 'एक स्टाफ या स्थानीय डिलीवरी राइडर के साथ टाई-अप करें।'
    ],
    [
        'id' => 68,
        'type' => 'Online',
        'title' => 'Home Delivery की जानकारी Online दें',
        'subtitle' => 'Highlight Home Delivery Availability Online',
        'desc' => 'अपनी प्रोफाइल, बैनर और सोशल मीडिया पर प्रमुखता से लिखें: "घर बैठे मंगाएं - फ्री होम डिलीवरी उपलब्ध!" इससे ऑर्डर तेजी से बढ़ते हैं।',
        'action' => 'WhatsApp पर ऑर्डर करने का आसान तरीका बताएं।'
    ],
    [
        'id' => 69,
        'type' => 'Offline',
        'title' => 'Delivery Area और Charges स्पष्ट रखें',
        'subtitle' => 'Specify Delivery Radius & Minimum Order Conditions',
        'desc' => 'स्पष्ट रखें कि कितने किलोमीटर तक फ्री डिलीवरी है और न्यूनतम ऑर्डर कितना होना चाहिए (जैसे "₹500 से ऊपर के ऑर्डर पर फ्री डिलीवरी")।',
        'action' => 'डिलीवरी के समय में पारदर्शिता रखें (जैसे "1 घंटे में आपके द्वार")।'
    ],
    [
        'id' => 70,
        'type' => 'Online',
        'title' => 'अपने Business की Story Share करें',
        'subtitle' => 'Share Your Authentic Brand Origin & Journey',
        'desc' => 'आपने यह बिज़नेस कब, क्यों और कैसे शुरू किया, आपने क्या चुनौतियां देखीं—अपनी कहानी सोशल मीडिया पर शेयर करें। लोग ब्रांड्स से नहीं, इंसानों से जुड़ते हैं।',
        'action' => 'ईमानदार कहानी ग्राहकों के मन में गहरा जुड़ाव पैदा करती है।'
    ],
    [
        'id' => 71,
        'type' => 'Offline',
        'title' => 'Business की पहचान और भरोसा बनाएं',
        'subtitle' => 'Build a Long-Term Reputation for Uncompromising Quality',
        'desc' => 'क्वालिटी और वजन में कभी समझौता न करें। मुनाफा थोड़ा कम हो लेकिन प्रतिष्ठा ऐसी हो कि लोग आंख बंद करके आपकी दुकान का नाम लें।',
        'action' => 'स्थानीय स्तर पर विश्वसनीयता सबसे बड़ी संपत्ति है।'
    ],
    [
        'id' => 72,
        'type' => 'Online',
        'title' => 'नए Products की Announcement करें',
        'subtitle' => 'Tease & Announce New Product Arrivals Online',
        'desc' => 'दुकान में नया माल आते ही उसकी फोटो और वीडियो बनाकर तुरंत स्टेटस और सोशल मीडिया पर पोस्ट करें: "नया स्टॉक आ चुका है, सीमित पीस उपलब्ध!"',
        'action' => 'उत्सुकता और जल्दी खरीदने (Urgency) की भावना पैदा करें।'
    ],
    [
        'id' => 73,
        'type' => 'Offline',
        'title' => 'नए Products का Demo दें',
        'subtitle' => 'Offer In-Store Live Demos & Product Trials',
        'desc' => 'ग्राहक को नए सामान का इस्तेमाल करके दिखाएं या ट्रायल का अवसर दें। जब ग्राहक अपनी आंखों से काम देखता है तो खरीदने का फैसला तुरंत लेता है।',
        'action' => 'डेमो के लिए काउंटर पर एक सैंपल पीस हमेशा तैयार रखें।'
    ],
    [
        'id' => 74,
        'type' => 'Online',
        'title' => 'Seasonal Demand के अनुसार Content बनाएं',
        'subtitle' => 'Align Marketing Content with Changing Seasons',
        'desc' => 'मौसम के अनुसार पोस्ट्स डालें (जैसे गर्मियों में कूल ड्रिंक्स/कॉटन कपड़े/AC सर्विस, सर्दियों में हीटर/गर्म कपड़े/गीजर, बारिश में रेनकोट/छतरी)।',
        'action' => 'मौसम बदलने से 15 दिन पहले ही कैंपेन शुरू कर दें।'
    ],
    [
        'id' => 75,
        'type' => 'Offline',
        'title' => 'Seasonal Stock पहले से रखें',
        'subtitle' => 'Procure Seasonal Inventory Well in Advance',
        'desc' => 'मौसम शुरू होने से पहले ही जरूरी स्टॉक मंगवा लें। जब सीजन की मांग चरम पर हो तो आपके पास पर्याप्त माल होना चाहिए ताकि ग्राहक न लौटे।',
        'action' => 'थोक में पहले खरीदने से बेहतर मार्जिन भी मिलता है।'
    ],
    [
        'id' => 76,
        'type' => 'Online',
        'title' => 'Online Advertising का छोटा Budget रखें',
        'subtitle' => 'Allocate a Small Budget for Hyper-Local Ads',
        'desc' => 'महीने में ₹500 - ₹1000 का छोटा बजट फेसबुक या इंस्टाग्राम ऐड्स के लिए रखें और सिर्फ अपने 5-10 किमी के सारण/छपरा क्षेत्र को टारगेट करें।',
        'action' => 'कम पैसे में हजारों स्थानीय लोगों तक आपकी दुकान का प्रचार होता है।'
    ],
    [
        'id' => 77,
        'type' => 'Offline',
        'title' => 'Local Banner/Pamphlet का सही उपयोग करें',
        'subtitle' => 'Deploy Targeted Local Banners & Newspaper Pamphlets',
        'desc' => 'स्थानीय अखबारों में पैम्फलेट डलवाएं या प्रमुख चौराहों पर छोटा साफ-सुथरा बैनर लगाएं जिसमें स्पष्ट ऑफर और मोबाइल नंबर लिखा हो।',
        'action' => 'पैम्फलेट में कोई विशेष कूपन या डिस्काउंट कोड जरूर दें।'
    ],
    [
        'id' => 78,
        'type' => 'Online',
        'title' => 'Advertisement का Result Track करें',
        'subtitle' => 'Track Which Online Channel Generates Inquiries',
        'desc' => 'देखें कि WhatsApp से कितने ग्राहक आए, Facebook से कितने आए और Saran Index डायरेक्ट्री से कितने कॉल आए। जो माध्यम सबसे अच्छा काम करे, उस पर ज्यादा ध्यान दें।',
        'action' => 'महीने के अंत में सबसे असरदार प्लेटफॉर्म की पहचान करें।'
    ],
    [
        'id' => 79,
        'type' => 'Offline',
        'title' => 'किस Promotion से Customer आया, पूछें',
        'subtitle' => 'Ask New In-Store Customers: "How Did You Hear About Us?"',
        'desc' => 'जब भी कोई नया ग्राहक दुकान पर आए, मुस्कुराकर पूछें: "आपको हमारी दुकान के बारे में कहां से पता चला?" (गूगल, बैनर, दोस्त, सारण इंडेक्स)।',
        'action' => 'इससे आपको पता चलेगा कि आपका कौन सा प्रचार सबसे ज्यादा फल दे रहा है।'
    ],
    [
        'id' => 80,
        'type' => 'Online',
        'title' => 'अपने सभी Online Links एक जगह रखें',
        'subtitle' => 'Consolidate All Links in One Central Bio Link',
        'desc' => 'अपनी Saran Index प्रोफाइल, WhatsApp, Facebook और Google Maps के सभी लिंक्स को एक जगह रखें ताकि ग्राहक किसी भी जगह से आपसे जुड़ सके।',
        'action' => 'विजिटिंग कार्ड और सोशल मीडिया बायो में Saran Index लिंक शेयर करें।'
    ],
    [
        'id' => 81,
        'type' => 'Offline',
        'title' => 'Customer से Feedback मांगें',
        'subtitle' => 'Regularly Seek Honest In-Person Customer Feedback',
        'desc' => 'खरीदारी के बाद ग्राहक से पूछें: "हमारी सर्विस और सामान कैसा लगा? कोई सुधार का सुझाव हो तो जरूर बताएं।" यह आपकी गंभीरता को दर्शाता है।',
        'action' => 'ग्राहक के सुझावों को डायरी या ऐप में नोट करें।'
    ],
    [
        'id' => 82,
        'type' => 'Online',
        'title' => 'Feedback के आधार पर बदलाव करें',
        'subtitle' => 'Implement Improvements Based on Feedback',
        'desc' => 'ग्राहकों से मिले सुझावों पर अमल करें और सोशल मीडिया पर पोस्ट करके बताएं: "आपके सुझाव पर हमने यह नया सुधार किया है।"',
        'action' => 'ग्राहक बहुत सम्मानित महसूस करते हैं जब उनकी बात सुनी जाती है।'
    ],
    [
        'id' => 83,
        'type' => 'Offline',
        'title' => 'कर्मचारियों के काम की नियमित समीक्षा करें',
        'subtitle' => 'Review Staff Performance & Customer Interactions',
        'desc' => 'महीने में एक बार अपने स्टाफ के साथ बैठकर बात करें। उनकी समस्याओं को सुनें, अच्छा काम करने वाले की तारीफ करें और बेहतर सर्विस के लिए प्रेरित करें।',
        'action' => 'खुश और प्रेरित स्टाफ ग्राहकों को बेहतरीन अनुभव देता है।'
    ],
    [
        'id' => 84,
        'type' => 'Online',
        'title' => 'Monthly Sales/Enquiry का Digital Record रखें',
        'subtitle' => 'Maintain Digital Monthly Sales & Enquiry Logs',
        'desc' => 'डिजिटल बहीखाता (जैसे Khatabook, Vyapar या Excel) का उपयोग करें। पता चलेगा कि पिछले महीने की तुलना में इस महीने कितनी सेल और मुनाफा हुआ।',
        'action' => 'कागजी बहीखाता खोने का डर रहता है, डिजिटल हमेशा सुरक्षित रहता है।'
    ],
    [
        'id' => 85,
        'type' => 'Offline',
        'title' => 'रोज की Sales और Expenses लिखें',
        'subtitle' => 'Record Daily Revenue & Expenses Diligently',
        'desc' => 'दुकान बंद करने से पहले रोज की कुल बिक्री, नकद, ऑनलाइन पेमेंट और छोटे-मोटे खर्चों का हिसाब उसी दिन लिख लें। हिसाब साफ तो व्यापार साफ।',
        'action' => 'रोजाना 10 मिनट हिसाब मिलाने की आदत वित्तीय स्थिरता लाती है।'
    ],
    [
        'id' => 86,
        'type' => 'Online',
        'title' => 'Important Business Data का Backup रखें',
        'subtitle' => 'Keep Cloud Backups of Critical Business Data',
        'desc' => 'कस्टमर लिस्ट, बिलिंग रिकॉर्ड और जरूरी दस्तावेजों का Google Drive या क्लाउड पर बैकअप रखें ताकि फोन खोने या खराब होने पर डेटा सुरक्षित रहे।',
        'action' => 'हफ्ते में एक बार ऑटोमैटिक बैकअप की जांच करें।'
    ],
    [
        'id' => 87,
        'type' => 'Offline',
        'title' => 'अनावश्यक खर्च कम करें',
        'subtitle' => 'Optimize Overheads & Eliminate Wasteful Expenses',
        'desc' => 'दुकान के फिजूल खर्चों पर नजर रखें (जैसे बिजली की बर्बादी, खराब पैकेजिंग, बिना जरूरत का सामान)। बचाई गई पाई-पाई मुनाफे में बदलती है।',
        'action' => 'बिजली बचाने के लिए आधुनिक LED लाइट्स और स्मार्ट उपयोग करें।'
    ],
    [
        'id' => 88,
        'type' => 'Online',
        'title' => 'महीने में एक बार अपनी Online Presence Check करें',
        'subtitle' => 'Audit Your Online Profiles & Information Monthly',
        'desc' => 'हर महीने गूगल, Saran Index और सोशल मीडिया पर अपनी प्रोफाइल खुद सर्च करके देखें कि फोन नंबर, पता, फोटो और रिव्यू सब सही और एक्टिव हैं या नहीं।',
        'action' => 'कोई भी गलत या पुरानी जानकारी तुरंत अपडेट करें।'
    ],
    [
        'id' => 89,
        'type' => 'Offline',
        'title' => 'हर महीने Business Growth का Review करें',
        'subtitle' => 'Conduct a Monthly Business Growth & Profit Review',
        'desc' => 'महीने के अंत में बैठें और सोचें: इस महीने कितने नए ग्राहक जुड़े? कौन सा प्रोडक्ट सबसे ज्यादा बिका? अगले महीने के लिए क्या नया लक्ष्य है?।',
        'action' => 'निरंतर समीक्षा ही व्यापार को छोटे से बड़ा बनाती है।'
    ],
    [
        'id' => 90,
        'type' => 'Both',
        'title' => 'Online पहचान + Offline भरोसा = मजबूत Local Business',
        'subtitle' => 'The Golden Formula: Online Visibility + In-Store Trust = Unstoppable Growth',
        'desc' => 'ऑनलाइन माध्यम से नए ग्राहकों को दुकान तक लाएं और अपनी बेहतरीन ऑफलाइन सर्विस, ईमानदारी और मुस्कान से उन्हें हमेशा के लिए अपना बना लें। यही सारण इंडेक्स का मूल मंत्र है!',
        'action' => 'आज ही Saran Index पर जुड़ें और अपने स्थानीय व्यापार को सफलता की नई ऊंचाइयों पर ले जाएं।'
    ]
];

// Stats calculation
$totalTips = count($tips);
$onlineCount = count(array_filter($tips, fn($t) => $t['type'] === 'Online'));
$offlineCount = count(array_filter($tips, fn($t) => $t['type'] === 'Offline'));
$bothCount = count(array_filter($tips, fn($t) => $t['type'] === 'Both'));

// Tip of the day calculation (1-90 based on day of year)
$dayOfYear = intval(date('z')) + 1;
$tipOfTheDayIndex = ($dayOfYear % $totalTips);
$dailyTip = $tips[$tipOfTheDayIndex];
?>

<!-- HERO SECTION -->
<div class="business-hero bg-dark text-white py-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, #090e17 0%, #111e38 50%, #1e3a8a 100%) !important;">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at 20% 20%, rgba(59, 130, 246, 0.18) 0%, transparent 50%), radial-gradient(circle at 80% 80%, rgba(245, 158, 11, 0.12) 0%, transparent 50%); pointer-events: none;"></div>
    
    <div class="container position-relative z-1 py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill bg-warning bg-opacity-10 border border-warning border-opacity-25 text-warning fw-bold small mb-3">
                    <i class="bi bi-award-fill"></i>
                    <span>SARAN INDEX BUSINESS ACCELERATOR • 90-DAY SERIES</span>
                </div>
                
                <h1 class="display-4 fw-bolder font-heading text-white mb-3 tracking-tight" style="line-height: 1.15;">
                    How <span class="text-warning">Saran Index</span> Helps in Business
                </h1>
                
                <p class="text-white-50 lead fs-5 mb-4" style="line-height: 1.6;">
                    Discover the ultimate <strong>90-Day Online + Offline Local Business Playbook</strong> designed for shops, traders, doctors, advocates, schools, and service providers across Chapra and all 20 blocks of Saran District, Bihar.
                </p>

                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="add-contact.php" class="btn btn-warning btn-lg rounded-pill px-4 py-3 fw-bold text-dark shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="bi bi-rocket-takeoff-fill"></i> List Your Business Free
                    </a>
                    <a href="#tips-explorer" class="btn btn-outline-light btn-lg rounded-pill px-4 py-3 fw-semibold d-inline-flex align-items-center gap-2">
                        <i class="bi bi-lightbulb-fill text-warning"></i> Explore 90 Business Tips
                    </a>
                    <a href="pricing.php" class="btn btn-outline-warning btn-lg rounded-pill px-4 py-3 fw-semibold d-inline-flex align-items-center gap-2">
                        <i class="bi bi-patch-check-fill"></i> Verified Plans
                    </a>
                </div>

                <!-- Live Quick Stats Badges -->
                <div class="row g-3 pt-2 text-white">
                    <div class="col-4 col-sm-3">
                        <div class="p-2.5 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10 text-center">
                            <div class="fs-4 fw-bolder text-warning font-heading">90</div>
                            <div class="small text-white-50" style="font-size: 0.75rem;">Action Tips</div>
                        </div>
                    </div>
                    <div class="col-4 col-sm-3">
                        <div class="p-2.5 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10 text-center">
                            <div class="fs-4 fw-bolder text-info font-heading">20</div>
                            <div class="small text-white-50" style="font-size: 0.75rem;">Saran Blocks</div>
                        </div>
                    </div>
                    <div class="col-4 col-sm-3">
                        <div class="p-2.5 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10 text-center">
                            <div class="fs-4 fw-bolder text-success font-heading">100%</div>
                            <div class="small text-white-50" style="font-size: 0.75rem;">Direct Leads</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-3 d-none d-sm-block">
                        <div class="p-2.5 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10 text-center">
                            <div class="fs-4 fw-bolder text-white font-heading">₹0</div>
                            <div class="small text-white-50" style="font-size: 0.75rem;">Free Entry</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tip of the Day Highlight Card -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(145deg, #1e293b 0%, #0f172a 100%); border: 1px solid rgba(255,255,255,0.15) !important;">
                    <div class="card-header border-0 bg-warning bg-opacity-10 py-3 px-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill">
                                <i class="bi bi-calendar-event me-1"></i> TIP OF THE DAY
                            </span>
                            <span class="text-white fw-bold small">Day #<?php echo sprintf('%02d', $dailyTip['id']); ?></span>
                        </div>
                        <span class="badge <?php echo $dailyTip['type'] === 'Online' ? 'bg-primary' : ($dailyTip['type'] === 'Offline' ? 'bg-success' : 'bg-warning text-dark'); ?> rounded-pill px-2.5 py-1 fw-bold small">
                            <?php echo $dailyTip['type']; ?>
                        </span>
                    </div>
                    <div class="card-body p-4 text-white">
                        <h4 class="fw-bold font-heading text-white mb-2" id="daily-tip-title">
                            <?php echo htmlspecialchars($dailyTip['title']); ?>
                        </h4>
                        <div class="text-warning small fw-semibold mb-3" id="daily-tip-subtitle">
                            <?php echo htmlspecialchars($dailyTip['subtitle']); ?>
                        </div>
                        <p class="text-white-50 small mb-3" style="line-height: 1.6;" id="daily-tip-desc">
                            <?php echo htmlspecialchars($dailyTip['desc']); ?>
                        </p>
                        <div class="p-3 rounded-3 bg-white bg-opacity-5 border border-white border-opacity-10 small text-light mb-4">
                            <i class="bi bi-check2-circle text-warning me-1.5"></i>
                            <strong class="text-warning">Action Step:</strong> <span id="daily-tip-action"><?php echo htmlspecialchars($dailyTip['action']); ?></span>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-success btn-sm rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center gap-1.5 shadow-sm" onclick="shareTipWhatsApp(<?php echo $dailyTip['id']; ?>)">
                                <i class="bi bi-whatsapp"></i> Share on WhatsApp
                            </button>
                            <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1.5" onclick="copyTipFormat(<?php echo $dailyTip['id']; ?>)">
                                <i class="bi bi-clipboard"></i> Copy Post
                            </button>
                            <button type="button" class="btn btn-outline-warning btn-sm rounded-pill px-3 py-2 fw-semibold ms-auto" onclick="showRandomTip()">
                                <i class="bi bi-shuffle me-1"></i> Random Tip
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 1: HOW SARAN INDEX HELPS LOCAL BUSINESSES (6 PILLARS) -->
<div class="container py-5">
    <div class="text-center max-w-750 mx-auto mb-5">
        <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-pill small mb-2">
            <i class="bi bi-stars me-1"></i> LOCAL COMMERCE EMPOWERMENT
        </span>
        <h2 class="fw-bold font-heading text-dark display-6 mb-3">
            Why Every Business in Saran Needs Saran Index
        </h2>
        <p class="text-secondary lead fs-6 mb-0">
            From Chapra City to the most remote villages of Sonpur, Marhaura, and Ekma, Saran Index bridges the gap between local customers and verified merchants.
        </p>
    </div>

    <div class="row g-4 mb-5">
        <!-- Pillar 1 -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white hover-shadow-lg transition-all border-top border-4 border-primary">
                <div class="bg-primary-subtle text-primary rounded-3 p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 54px; height: 54px;">
                    <i class="bi bi-geo-alt-fill fs-3"></i>
                </div>
                <h4 class="fw-bold font-heading text-dark fs-5 mb-2">1. Hyper-Local Discovery</h4>
                <p class="text-muted small mb-0" style="line-height: 1.6;">
                    Organized by all <strong>20 Blocks, 300+ Panchayats, and 1,700+ Villages</strong> of Saran. Customers in your exact locality find your shop when they need it most.
                </p>
            </div>
        </div>

        <!-- Pillar 2 -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white hover-shadow-lg transition-all border-top border-4 border-success">
                <div class="bg-success-subtle text-success rounded-3 p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 54px; height: 54px;">
                    <i class="bi bi-whatsapp fs-3"></i>
                </div>
                <h4 class="fw-bold font-heading text-dark fs-5 mb-2">2. Direct Call & WhatsApp Leads</h4>
                <p class="text-muted small mb-0" style="line-height: 1.6;">
                    Zero friction communication. Customers click one button to <strong>call your mobile</strong> or start a <strong>WhatsApp conversation</strong> without any intermediate delays.
                </p>
            </div>
        </div>

        <!-- Pillar 3 -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white hover-shadow-lg transition-all border-top border-4 border-warning">
                <div class="bg-warning-subtle text-warning-emphasis rounded-3 p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 54px; height: 54px;">
                    <i class="bi bi-patch-check-fill fs-3"></i>
                </div>
                <h4 class="fw-bold font-heading text-dark fs-5 mb-2">3. Verified Trust Badges</h4>
                <p class="text-muted small mb-0" style="line-height: 1.6;">
                    Stand out from unverified sellers. Our <strong>Verified Business Badge</strong> builds immediate credibility so customers feel safe buying from you.
                </p>
            </div>
        </div>

        <!-- Pillar 4 -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white hover-shadow-lg transition-all border-top border-4 border-info">
                <div class="bg-info-subtle text-info-emphasis rounded-3 p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 54px; height: 54px;">
                    <i class="bi bi-search fs-3"></i>
                </div>
                <h4 class="fw-bold font-heading text-dark fs-5 mb-2">4. Free SEO & Google Indexing</h4>
                <p class="text-muted small mb-0" style="line-height: 1.6;">
                    Every listing gets indexed on Google with clean schema metadata. When someone searches <em>"Best Doctor in Chapra"</em> or <em>"Hardware store Marhaura"</em>, your profile ranks.
                </p>
            </div>
        </div>

        <!-- Pillar 5 -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white hover-shadow-lg transition-all border-top border-4 border-danger">
                <div class="bg-danger-subtle text-danger rounded-3 p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 54px; height: 54px;">
                    <i class="bi bi-percent fs-3"></i>
                </div>
                <h4 class="fw-bold font-heading text-dark fs-5 mb-2">5. 0% Commission & Middlemen</h4>
                <p class="text-muted small mb-0" style="line-height: 1.6;">
                    Keep 100% of your earnings. Unlike aggregator apps that charge 20-30% commissions per order or lead, Saran Index provides pure, direct, unhindered business connections.
                </p>
            </div>
        </div>

        <!-- Pillar 6 -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white hover-shadow-lg transition-all border-top border-4 border-dark">
                <div class="bg-dark text-white rounded-3 p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 54px; height: 54px;">
                    <i class="bi bi-qr-code-scan fs-3"></i>
                </div>
                <h4 class="fw-bold font-heading text-dark fs-5 mb-2">6. 24x7 Digital Storefront</h4>
                <p class="text-muted small mb-0" style="line-height: 1.6;">
                    Your custom Saran Index profile acts as your permanent digital brochure. Share it on WhatsApp, print the QR code on your visiting card, and showcase photos anytime.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 2: THE 90 BUSINESS TIPS EXPLORER & PLAYBOOK -->
<div class="bg-light py-5 border-top border-bottom" id="tips-explorer">
    <div class="container">
        
        <!-- Header & Series Summary -->
        <div class="row align-items-end g-4 mb-4">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill small mb-2">
                    <i class="bi bi-journal-check me-1"></i> 90-DAY COMPLETE CURRICULUM
                </span>
                <h2 class="fw-bold font-heading text-dark display-6 mb-2">
                    90 Business Tips: Online & Offline Growth Series
                </h2>
                <p class="text-muted mb-0">
                    Use these 90 practical tips to modernize your operations, enhance customer satisfaction, and build an unbreakable local brand in Saran.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="d-inline-flex align-items-center gap-2 p-2 bg-white rounded-pill shadow-xs border">
                    <span class="badge bg-primary rounded-pill px-2.5 py-1">Online: <?php echo $onlineCount; ?></span>
                    <span class="badge bg-success rounded-pill px-2.5 py-1">Offline: <?php echo $offlineCount; ?></span>
                    <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1">Both: <?php echo $bothCount; ?></span>
                </div>
            </div>
        </div>

        <!-- Controls: Filters, Search & Jump Menu -->
        <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 mb-4 bg-white">
            <div class="row g-3 align-items-center">
                
                <!-- Category Filter Buttons -->
                <div class="col-lg-6 col-md-12">
                    <div class="btn-group w-100 flex-wrap" role="group" id="type-filter-group">
                        <button type="button" class="btn btn-outline-dark active rounded-pill px-3 py-2 fw-semibold filter-btn me-1 mb-1" onclick="filterTips('All')">
                            All Tips <span class="badge bg-dark text-white rounded-pill ms-1"><?php echo $totalTips; ?></span>
                        </button>
                        <button type="button" class="btn btn-outline-primary rounded-pill px-3 py-2 fw-semibold filter-btn me-1 mb-1" onclick="filterTips('Online')">
                            <i class="bi bi-globe me-1"></i> Online <span class="badge bg-primary text-white rounded-pill ms-1"><?php echo $onlineCount; ?></span>
                        </button>
                        <button type="button" class="btn btn-outline-success rounded-pill px-3 py-2 fw-semibold filter-btn me-1 mb-1" onclick="filterTips('Offline')">
                            <i class="bi bi-shop me-1"></i> Offline <span class="badge bg-success text-white rounded-pill ms-1"><?php echo $offlineCount; ?></span>
                        </button>
                        <button type="button" class="btn btn-outline-warning text-dark rounded-pill px-3 py-2 fw-semibold filter-btn mb-1" onclick="filterTips('Both')">
                            <i class="bi bi-star-fill me-1"></i> Golden Rule <span class="badge bg-warning text-dark rounded-pill ms-1">#90</span>
                        </button>
                    </div>
                </div>

                <!-- Instant Search Bar -->
                <div class="col-lg-6 col-md-12">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 rounded-start-pill ps-3">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" id="tip-search-input" class="form-control bg-light border-start-0 rounded-end-pill py-2.5" placeholder="Search tips by keyword (e.g. WhatsApp, Google, Board, Stock, Review, Discount)..." onkeyup="searchTips()">
                    </div>
                </div>
            </div>

            <!-- Quick Jump Pagination Anchors (1-10, 11-20, ... 81-90) -->
            <div class="d-flex align-items-center gap-1.5 flex-wrap pt-3 mt-2 border-top">
                <span class="small fw-bold text-muted me-2"><i class="bi bi-fast-forward me-1"></i>Jump:</span>
                <?php for ($range = 1; $range <= 90; $range += 10): 
                    $endRange = min($range + 9, 90);
                ?>
                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-2.5 py-1 small fw-semibold jump-btn" onclick="jumpToTip(<?php echo $range; ?>)">
                        #<?php echo sprintf('%02d', $range); ?>–<?php echo sprintf('%02d', $endRange); ?>
                    </button>
                <?php endfor; ?>
            </div>
        </div>

        <!-- TIPS GRID -->
        <div class="row g-4" id="tips-container">
            <?php foreach ($tips as $tip): ?>
                <div class="col-lg-4 col-md-6 tip-item" 
                     id="tip-card-<?php echo $tip['id']; ?>"
                     data-id="<?php echo $tip['id']; ?>" 
                     data-type="<?php echo $tip['type']; ?>" 
                     data-title="<?php echo htmlspecialchars(mb_strtolower($tip['title'] . ' ' . $tip['subtitle'] . ' ' . $tip['desc'] . ' ' . $tip['action'])); ?>">
                    
                    <div class="card h-100 border-0 shadow-sm rounded-4 bg-white p-4 d-flex flex-column justify-content-between hover-shadow-md transition-all position-relative overflow-hidden <?php echo $tip['type'] === 'Both' ? 'border border-2 border-warning' : ''; ?>">
                        
                        <!-- Top Accent Bar -->
                        <div class="position-absolute top-0 start-0 w-100" style="height: 4px; background: <?php echo $tip['type'] === 'Online' ? '#2563eb' : ($tip['type'] === 'Offline' ? '#16a34a' : '#f59e0b'); ?>;"></div>

                        <div>
                            <!-- Header Info -->
                            <div class="d-flex align-items-center justify-content-between mb-3 pt-1">
                                <span class="badge bg-dark text-white fw-bolder px-2.5 py-1 rounded-pill small font-heading">
                                    TIP #<?php echo sprintf('%02d', $tip['id']); ?>
                                </span>
                                <span class="badge <?php echo $tip['type'] === 'Online' ? 'bg-primary-subtle text-primary border border-primary-subtle' : ($tip['type'] === 'Offline' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-warning-emphasis border border-warning'); ?> rounded-pill px-2.5 py-1 fw-bold small">
                                    <i class="bi <?php echo $tip['type'] === 'Online' ? 'bi-globe' : ($tip['type'] === 'Offline' ? 'bi-shop' : 'bi-stars'); ?> me-1"></i><?php echo $tip['type']; ?>
                                </span>
                            </div>

                            <!-- Title in Hindi -->
                            <h3 class="fw-bold font-heading text-dark fs-5 mb-1" style="line-height: 1.35;">
                                <?php echo htmlspecialchars($tip['title']); ?>
                            </h3>

                            <!-- Subtitle in English -->
                            <div class="small text-muted fw-semibold mb-3">
                                <?php echo htmlspecialchars($tip['subtitle']); ?>
                            </div>

                            <!-- Description -->
                            <p class="text-secondary small mb-3" style="line-height: 1.6;">
                                <?php echo htmlspecialchars($tip['desc']); ?>
                            </p>

                            <!-- Practical Action Step -->
                            <div class="p-2.5 rounded-3 bg-light border border-light-subtle small text-dark mb-4">
                                <i class="bi bi-arrow-right-circle-fill <?php echo $tip['type'] === 'Online' ? 'text-primary' : ($tip['type'] === 'Offline' ? 'text-success' : 'text-warning'); ?> me-1"></i>
                                <strong>Action:</strong> <?php echo htmlspecialchars($tip['action']); ?>
                            </div>
                        </div>

                        <!-- Footer Share & Copy Actions -->
                        <div class="pt-3 border-top d-flex align-items-center justify-content-between gap-2">
                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1 flex-grow-1 justify-content-center" onclick="shareTipWhatsApp(<?php echo $tip['id']; ?>)">
                                <i class="bi bi-whatsapp"></i> Share
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1" onclick="copyTipFormat(<?php echo $tip['id']; ?>)" title="Copy Post Format">
                                <i class="bi bi-clipboard"></i> Copy
                            </button>
                            <button type="button" class="btn btn-sm btn-light border rounded-circle p-1.5" onclick="previewTipModal(<?php echo $tip['id']; ?>)" title="View Template Format" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- No Results Found Alert -->
        <div id="no-tips-found" class="text-center py-5 d-none">
            <div class="bg-white p-5 rounded-4 shadow-sm max-w-500 mx-auto">
                <i class="bi bi-search text-muted display-4 d-block mb-3"></i>
                <h4 class="fw-bold text-dark font-heading">No Business Tips Found</h4>
                <p class="text-muted small mb-3">Try searching for keywords like "WhatsApp", "Google", "Customer", "Stock", or "Review".</p>
                <button type="button" class="btn btn-primary rounded-pill px-4 py-2 btn-sm fw-bold" onclick="resetSearch()">
                    Clear Search & View All 90 Tips
                </button>
            </div>
        </div>

    </div>
</div>

<!-- SECTION 3: THE SARAN INDEX POST TEMPLATE & FORMAT -->
<div class="container py-5">
    <div class="row align-items-center g-5">
        <div class="col-lg-6">
            <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill small mb-2">
                <i class="bi bi-share-fill me-1"></i> DAILY SOCIAL CONTENT ENGINE
            </span>
            <h2 class="fw-bold font-heading text-dark display-6 mb-3">
                Turn Every Tip into a Viral WhatsApp & Social Post
            </h2>
            <p class="text-secondary lead fs-6 mb-4" style="line-height: 1.7;">
                All 90 tips follow our proven, consistent branding format. You can publish 1 tip daily to your <strong>WhatsApp Status, Facebook Page, Instagram Reels, and Telegram Channels</strong> to educate your customers and build authority.
            </p>

            <div class="d-flex flex-column gap-3 mb-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">
                        <i class="bi bi-check-lg fw-bold"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Instant 1-Click WhatsApp Sharing</h6>
                        <p class="text-muted small mb-0">Pre-formatted with clean emojis, clear Hindi text, and verified website links.</p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3">
                    <div class="bg-success text-white rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">
                        <i class="bi bi-check-lg fw-bold"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Empower Your Staff & Network</h6>
                        <p class="text-muted small mb-0">Share with shop attendants, fellow traders, and business groups to raise service standards.</p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3">
                    <div class="bg-warning text-dark rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">
                        <i class="bi bi-check-lg fw-bold"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Golden Rule #90: Online + Offline Synergy</h6>
                        <p class="text-muted small mb-0">Combine digital presence with offline warmth for unbeatable long-term business retention.</p>
                    </div>
                </div>
            </div>

            <a href="add-contact.php" class="btn btn-primary rounded-pill px-4 py-3 fw-bold shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Register on SaranIndex.com
            </a>
        </div>

        <!-- Live Interactive Post Preview Card -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #182234 0%, #0d131f 100%); border: 1px solid rgba(255,255,255,0.1) !important;">
                <div class="card-header bg-dark border-bottom border-secondary py-3 px-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-success rounded-circle" style="width: 12px; height: 12px;"></div>
                        <span class="text-white small fw-bold">Official Series Post Format</span>
                    </div>
                    <span class="badge bg-warning text-dark fw-bold rounded-pill px-2 py-0.5" style="font-size: 0.72rem;">SaranIndex.com</span>
                </div>
                <div class="card-body p-4 text-white font-monospace small" style="line-height: 1.8;">
                    <div class="text-warning fw-bold fs-6 mb-2">💡 Business Tip #01</div>
                    <div class="fw-bolder text-white fs-5 mb-3 font-heading font-sans">Google पर अपना Business List करें</div>
                    <div class="text-white-50 mb-4 font-sans">
                        आपका ग्राहक आपके Business को Internet पर आसानी से खोज सके—इसके लिए अपनी सही Business Information Online रखें।
                    </div>
                    
                    <div class="p-3 rounded-3 bg-white bg-opacity-5 border border-white border-opacity-10 text-light mb-4 font-sans">
                        <div>📍 <strong>अपने Business को Digital बनाएं</strong></div>
                        <div>🌐 <strong>SaranIndex.com</strong></div>
                        <div class="text-warning">✨ <strong>Connecting Saran Digitally</strong></div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success btn-sm rounded-pill px-3 py-2 fw-bold w-100" onclick="shareTipWhatsApp(1)">
                            <i class="bi bi-whatsapp me-1"></i> Share Tip #01 on WhatsApp
                        </button>
                        <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3 py-2 fw-semibold" onclick="copyTipFormat(1)">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 4: COMPARISON: LOCAL BUSINESS WITH VS WITHOUT SARAN INDEX -->
<div class="bg-white py-5 border-top">
    <div class="container">
        <div class="text-center max-w-750 mx-auto mb-5">
            <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-pill small mb-2">
                <i class="bi bi-bar-chart-fill me-1"></i> MEASURABLE IMPACT
            </span>
            <h2 class="fw-bold font-heading text-dark display-6 mb-3">
                Without Saran Index vs With Saran Index
            </h2>
            <p class="text-secondary lead fs-6 mb-0">
                See why hundreds of local business owners in Saran are registering and upgrading to Verified status.
            </p>
        </div>

        <div class="row g-4 align-items-stretch">
            <!-- Unregistered / Traditional -->
            <div class="col-lg-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-light">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-danger text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="bi bi-x-lg fw-bold"></i>
                        </div>
                        <h4 class="fw-bold text-danger font-heading mb-0">Without Saran Index</h4>
                    </div>
                    <ul class="list-unstyled mb-0 small text-secondary" style="line-height: 2.2;">
                        <li><i class="bi bi-x-circle-fill text-danger me-2"></i> Limited to passersby and immediate street footfall</li>
                        <li><i class="bi bi-x-circle-fill text-danger me-2"></i> Invisible on Google searches for block / village keywords</li>
                        <li><i class="bi bi-x-circle-fill text-danger me-2"></i> No official verified badge to build instant buyer trust</li>
                        <li><i class="bi bi-x-circle-fill text-danger me-2"></i> Customers struggle to find phone number or exact address</li>
                        <li><i class="bi bi-x-circle-fill text-danger me-2"></i> High reliance on expensive paper ads and banners</li>
                        <li><i class="bi bi-x-circle-fill text-danger me-2"></i> No easy way for rural customers in neighboring blocks to discover you</li>
                    </ul>
                </div>
            </div>

            <!-- With Saran Index -->
            <div class="col-lg-6">
                <div class="card h-100 border-2 border-primary shadow-md rounded-4 p-4 bg-white position-relative" style="background: linear-gradient(180deg, #f0f7ff 0%, #ffffff 100%) !important;">
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-primary text-white fw-bold px-3 py-1 rounded-pill">Recommended</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-success text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="bi bi-check-lg fw-bold"></i>
                        </div>
                        <h4 class="fw-bold text-primary font-heading mb-0">With Saran Index Directory</h4>
                    </div>
                    <ul class="list-unstyled mb-0 small text-dark" style="line-height: 2.2;">
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Accessible to 40 Lakh+ citizens</strong> across all 20 Saran blocks</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Top ranking on Google & Saran Index search</strong> with block & category</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Verified Business Trust Badge</strong> that multiplies conversions</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>1-Click Direct Call & WhatsApp Chat</strong> for instant inquiries</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>100% Free Lifetime Basic Listing</strong> with zero hidden fees</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Complete digital brochure</strong> with photos, timings, and map location</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 5: HOW TO REGISTER IN 3 SIMPLE STEPS -->
<div class="container py-5">
    <div class="text-center max-w-750 mx-auto mb-5">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill small mb-2">
            <i class="bi bi-lightning-charge-fill me-1"></i> QUICK ONBOARDING
        </span>
        <h2 class="fw-bold font-heading text-dark display-6 mb-3">
            Register Your Business in 3 Simple Steps
        </h2>
        <p class="text-secondary lead fs-6 mb-0">
            Join Saran's largest business network in under 2 minutes. Free and verified plans available.
        </p>
    </div>

    <div class="row g-4 text-center">
        <!-- Step 1 -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-light">
                <div class="bg-primary text-white rounded-circle fs-3 fw-bold d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                    1
                </div>
                <h4 class="fw-bold font-heading text-dark fs-5 mb-2">Create Free Account</h4>
                <p class="text-muted small mb-0">
                    Sign up with your mobile number and name in 30 seconds. No complicated paperwork required.
                </p>
            </div>
        </div>

        <!-- Step 2 -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-light">
                <div class="bg-warning text-dark rounded-circle fs-3 fw-bold d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                    2
                </div>
                <h4 class="fw-bold font-heading text-dark fs-5 mb-2">Add Business Details</h4>
                <p class="text-muted small mb-0">
                    Enter your store name, block, address, WhatsApp number, working hours, and upload photos.
                </p>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-light">
                <div class="bg-success text-white rounded-circle fs-3 fw-bold d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                    3
                </div>
                <h4 class="fw-bold font-heading text-dark fs-5 mb-2">Get Discovered & Grow</h4>
                <p class="text-muted small mb-0">
                    Your profile goes live immediately. Start receiving direct customer calls and WhatsApp inquiries!
                </p>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <a href="add-contact.php" class="btn btn-warning btn-lg rounded-pill px-5 py-3 fw-bold text-dark shadow-sm me-2 mb-2">
            <i class="bi bi-rocket-takeoff-fill me-1"></i> List My Business Free
        </a>
        <a href="pricing.php" class="btn btn-outline-primary btn-lg rounded-pill px-4 py-3 fw-bold mb-2">
            <i class="bi bi-shield-check me-1"></i> View Verified Membership Plans
        </a>
    </div>
</div>

<!-- SECTION 6: FREQUENTLY ASKED QUESTIONS (FAQ) -->
<div class="bg-light py-5 border-top">
    <div class="container">
        <div class="text-center max-w-750 mx-auto mb-5">
            <span class="badge bg-secondary-subtle text-secondary fw-bold px-3 py-1.5 rounded-pill small mb-2">
                <i class="bi bi-question-circle-fill me-1"></i> FAQ
            </span>
            <h2 class="fw-bold font-heading text-dark display-6 mb-3">
                Frequently Asked Questions
            </h2>
            <p class="text-muted small mb-0">
                Common questions from business owners about listing and promotion in Saran District.
            </p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion accordion-flush bg-white rounded-4 shadow-sm p-3" id="businessFaqAccordion">
                    
                    <div class="accordion-item border-bottom py-2">
                        <h3 class="accordion-header">
                            <button class="accordion-button fw-bold text-dark fs-6 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                क्या Saran Index पर बिज़नेस लिस्ट करना बिल्कुल फ्री है?
                            </button>
                        </h3>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#businessFaqAccordion">
                            <div class="accordion-body text-secondary small" style="line-height: 1.7;">
                                <strong>हाँ, बिल्कुल!</strong> बेसिक बिज़नेस लिस्टिंग 100% फ्री है। आप अपना नाम, पता, फोन नंबर, प्रखंड और श्रेणी आसानी से जोड़ सकते हैं। यदि आप टॉप सर्च रैंकिंग, वेरिफाइड ट्रस्ट बैज और डायरेक्ट व्हाट्सएप बटन चाहते हैं तो किफायती गोल्ड या वीआईपी प्लान चुन सकते हैं।
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-bottom py-2">
                        <h3 class="accordion-header">
                            <button class="accordion-button fw-bold text-dark fs-6 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                कौन-कौन से बिज़नेस Saran Index पर लिस्ट हो सकते हैं?
                            </button>
                        </h3>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#businessFaqAccordion">
                            <div class="accordion-body text-secondary small" style="line-height: 1.7;">
                                सारण जिले की सभी प्रकार की रिटेल दुकानें, थोक व्यापारी, क्लिनिक, डॉक्टर्स, अधिवक्ता, स्कूल, कोचिंग संस्थान, होटल, रेस्टोरेंट, ब्यूटी पार्लर, इलेक्ट्रिशियन, प्लंबर, कारपेंटर और होम सर्विस प्रोवाइडर्स लिस्ट हो सकते हैं।
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-bottom py-2">
                        <h3 class="accordion-header">
                            <button class="accordion-button fw-bold text-dark fs-6 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                90 Business Tips का उपयोग मैं अपने बिज़नेस में कैसे करूँ?
                            </button>
                        </h3>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#businessFaqAccordion">
                            <div class="accordion-body text-secondary small" style="line-height: 1.7;">
                                आप रोज 1 टिप पढ़कर उसे अपनी दुकान या ऑनलाइन प्रोफाइल पर लागू कर सकते हैं। इसके अलावा आप "Share on WhatsApp" बटन से इस टिप को अपने WhatsApp Status और सोशल मीडिया पर शेयर करके अपने ग्राहकों को भी जागरूक कर सकते हैं।
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-bottom py-2">
                        <h3 class="accordion-header">
                            <button class="accordion-button fw-bold text-dark fs-6 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                क्या ग्रामीण क्षेत्रों (जैसे मांझी, पानापुर, तरैया) की दुकानें भी लिस्ट हो सकती हैं?
                            </button>
                        </h3>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#businessFaqAccordion">
                            <div class="accordion-body text-secondary small" style="line-height: 1.7;">
                                बिल्कुल! Saran Index सारण के सभी 20 प्रखंडों और 300 से अधिक पंचायतों के लिए विशेष रूप से डिजाइन किया गया है। ग्रामीण बाजारों और कस्बों की दुकानों को डिजिटल पहचान देना ही हमारा मुख्य उद्देश्य है।
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item py-2">
                        <h3 class="accordion-header">
                            <button class="accordion-button fw-bold text-dark fs-6 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                क्या ग्राहकों से कोई कमीशन काटा जाता है?
                            </button>
                        </h3>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#businessFaqAccordion">
                            <div class="accordion-body text-secondary small" style="line-height: 1.7;">
                                <strong>नहीं, शून्य कमीशन!</strong> ग्राहक सीधे आपके फोन नंबर पर कॉल करते हैं या व्हाट्सएप पर बात करते हैं। आपके और ग्राहक के बीच कोई बिचौलिया नहीं होता।
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- TIP DETAIL MODAL -->
<div class="modal fade" id="tipDetailModal" tabindex="-1" aria-labelledby="tipModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <div class="modal-header bg-dark text-white border-0 py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-warning text-dark fw-bold rounded-pill px-2.5 py-1" id="modal-tip-id">TIP #01</span>
                    <span class="badge bg-primary rounded-pill px-2.5 py-1" id="modal-tip-type">Online</span>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <h4 class="fw-bold font-heading text-dark mb-1" id="modal-tip-title">Tip Title</h4>
                <div class="text-muted small fw-semibold mb-3" id="modal-tip-subtitle">Subtitle</div>
                
                <div class="p-3 rounded-3 bg-light border mb-3 small" style="line-height: 1.6;" id="modal-tip-desc">
                    Description goes here.
                </div>

                <div class="p-3 rounded-3 bg-warning-subtle border border-warning text-dark small mb-3">
                    <i class="bi bi-check2-circle text-warning-emphasis me-1"></i>
                    <strong>Action Step:</strong> <span id="modal-tip-action">Action goes here.</span>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">WhatsApp / Social Media Share Format:</label>
                    <textarea class="form-control font-monospace small bg-light" id="modal-tip-copy-area" rows="6" readonly></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 py-3 px-4 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-2 btn-sm fw-semibold" data-bs-dismiss="modal">Close</button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary rounded-pill px-3 py-2 btn-sm fw-bold" id="modal-copy-btn" onclick="copyModalContent()">
                        <i class="bi bi-clipboard me-1"></i> Copy Format
                    </button>
                    <button type="button" class="btn btn-success rounded-pill px-3 py-2 btn-sm fw-bold" id="modal-whatsapp-btn" onclick="shareModalWhatsApp()">
                        <i class="bi bi-whatsapp me-1"></i> Share on WhatsApp
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TOAST NOTIFICATION FOR COPY -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
    <div id="copyToast" class="toast align-items-center text-white bg-dark border-0 rounded-4 shadow" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                <span id="toastMessage">Tip format copied to clipboard!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- CLIENT JAVASCRIPT FOR DYNAMIC FILTERING & WHATSAPP SHARING -->
<script>
const TIPS_DATA = <?php echo json_encode($tips, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
let currentModalTipId = 1;

// Format tip for WhatsApp and Social Media matching Saran Index Series format
function getFormattedTipText(tipId) {
    const tip = TIPS_DATA.find(t => t.id === tipId);
    if (!tip) return '';

    const paddedId = String(tip.id).padStart(2, '0');
    return `💡 *Business Tip #${paddedId}*
*${tip.title}*

${tip.desc}

👉 *Action Step:* ${tip.action}

📍 *अपने Business को Digital बनाएं*
🌐 *SaranIndex.com*
✨ *Connecting Saran Digitally*`;
}

// 1-Click WhatsApp Share
function shareTipWhatsApp(tipId) {
    const text = getFormattedTipText(tipId);
    const encoded = encodeURIComponent(text);
    const waUrl = `https://api.whatsapp.com/send?text=${encoded}`;
    window.open(waUrl, '_blank');
}

// 1-Click Copy to Clipboard
function copyTipFormat(tipId) {
    const text = getFormattedTipText(tipId);
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(() => {
            showToast(`Business Tip #${String(tipId).padStart(2, '0')} copied to clipboard!`);
        });
    } else {
        // Fallback for non-https or older browsers
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.left = '-9999px';
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showToast(`Business Tip #${String(tipId).padStart(2, '0')} copied!`);
    }
}

// Show Toast Message
function showToast(msg) {
    const toastEl = document.getElementById('copyToast');
    const msgEl = document.getElementById('toastMessage');
    if (msgEl) msgEl.innerText = msg;
    if (toastEl && window.bootstrap) {
        const toast = new bootstrap.Toast(toastEl, { delay: 2500 });
        toast.show();
    }
}

// Filter Tips by Type (All, Online, Offline, Both)
function filterTips(type) {
    // Update active button
    const buttons = document.querySelectorAll('#type-filter-group .filter-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    
    event?.currentTarget?.classList.add('active');

    const items = document.querySelectorAll('.tip-item');
    let visibleCount = 0;

    items.forEach(item => {
        const itemType = item.getAttribute('data-type');
        if (type === 'All' || itemType === type) {
            item.classList.remove('d-none');
            visibleCount++;
        } else {
            item.classList.add('d-none');
        }
    });

    // Check if none visible
    const noResults = document.getElementById('no-tips-found');
    if (noResults) {
        if (visibleCount === 0) {
            noResults.classList.remove('d-none');
        } else {
            noResults.classList.add('d-none');
        }
    }
}

// Live Search in Tips
function searchTips() {
    const query = document.getElementById('tip-search-input').value.toLowerCase().trim();
    const items = document.querySelectorAll('.tip-item');
    let visibleCount = 0;

    items.forEach(item => {
        const text = item.getAttribute('data-title');
        if (!query || text.includes(query)) {
            item.classList.remove('d-none');
            visibleCount++;
        } else {
            item.classList.add('d-none');
        }
    });

    const noResults = document.getElementById('no-tips-found');
    if (noResults) {
        if (visibleCount === 0) {
            noResults.classList.remove('d-none');
        } else {
            noResults.classList.add('d-none');
        }
    }
}

// Reset Search
function resetSearch() {
    const input = document.getElementById('tip-search-input');
    if (input) input.value = '';
    filterTips('All');
}

// Smooth Jump to Tip Card
function jumpToTip(tipId) {
    const target = document.getElementById(`tip-card-${tipId}`);
    if (target) {
        target.classList.remove('d-none');
        target.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Highlight animation
        const card = target.querySelector('.card');
        if (card) {
            card.classList.add('shadow-lg', 'border-primary');
            setTimeout(() => {
                card.classList.remove('shadow-lg', 'border-primary');
            }, 1800);
        }
    }
}

// Random Tip in Hero
function showRandomTip() {
    const randomIndex = Math.floor(Math.random() * TIPS_DATA.length);
    const randomTip = TIPS_DATA[randomIndex];
    
    document.getElementById('daily-tip-title').innerText = randomTip.title;
    document.getElementById('daily-tip-subtitle').innerText = randomTip.subtitle;
    document.getElementById('daily-tip-desc').innerText = randomTip.desc;
    document.getElementById('daily-tip-action').innerText = randomTip.action;
}

// Preview Modal
function previewTipModal(tipId) {
    const tip = TIPS_DATA.find(t => t.id === tipId);
    if (!tip) return;

    currentModalTipId = tipId;
    document.getElementById('modal-tip-id').innerText = `TIP #${String(tip.id).padStart(2, '0')}`;
    document.getElementById('modal-tip-type').innerText = tip.type;
    document.getElementById('modal-tip-title').innerText = tip.title;
    document.getElementById('modal-tip-subtitle').innerText = tip.subtitle;
    document.getElementById('modal-tip-desc').innerText = tip.desc;
    document.getElementById('modal-tip-action').innerText = tip.action;
    document.getElementById('modal-tip-copy-area').value = getFormattedTipText(tipId);

    const modalEl = document.getElementById('tipDetailModal');
    if (modalEl && window.bootstrap) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
}

function copyModalContent() {
    copyTipFormat(currentModalTipId);
}

function shareModalWhatsApp() {
    shareTipWhatsApp(currentModalTipId);
}
</script>

<style>
.font-sans { font-family: 'Inter', system-ui, -apple-system, sans-serif !important; }
.tracking-tight { letter-spacing: -0.02em; }
.max-w-750 { max-width: 750px; }
.max-w-500 { max-width: 500px; }
.hover-shadow-md:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important; }
.hover-shadow-lg:hover { transform: translateY(-4px); box-shadow: 0 15px 30px rgba(0,0,0,0.12) !important; }
.transition-all { transition: all 0.25s ease-in-out; }
.jump-btn:hover { background-color: #0f172a; color: #fff; }
</style>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
