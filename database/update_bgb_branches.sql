-- =====================================================================
-- Saran Index - Update SQL for Bihar Gramin Bank (BGB) 80 Branches
-- Purpose:
--   1. Set mobile_visibility = 'REGISTERED' (Masked for guests/visitors)
--   2. Set email_visibility = 'REGISTERED' (Masked for guests/visitors)
--   3. Remove phone numbers & email addresses completely from description
--   4. Link to official source (source = 'bgb.bank.in', source_id = 22)
-- Generated: 2026-10-31 20:33:50
-- =====================================================================

-- ---------------------------------------------------------------------
-- Step 1: Ensure BGB entry exists in `sources` table
-- ---------------------------------------------------------------------
INSERT INTO `sources` (`id`, `title`, `title_hindi`, `subtitle`, `subtitle_hindi`, `description`, `description_hindi`, `badge_text`, `badge_text_hindi`, `badge_icon`, `badge_color_class`, `authority_badge`, `authority_badge_hindi`, `domain`, `url`, `sort_order`, `status`, `created_at`, `updated_at`)
VALUES (22, 'Bihar Gramin Bank Official Branch Locator', 'बिहार ग्रामीण बैंक आधिकारिक शाखा लोकेटर', 'Bihar Gramin Bank (Sponsored by Punjab National Bank)', 'बिहार ग्रामीण बैंक (पंजाब नेशनल बैंक द्वारा प्रायोजित)', 'Official branch registry and locator directory for Bihar Gramin Bank (BGB) branches across Saran District (Chapra Region), providing verified SOL IDs, regional offices, and rural banking centers.', 'सारण जिले (छपरा प्रक्षेत्र) में संचालित बिहार ग्रामीण बैंक की सभी शाखाओं, आधिकारिक SOL ID, क्षेत्रीय कार्यालय और ग्रामीण बैंकिंग केंद्रों का आधिकारिक लोकेटर डेटा।', 'BGB Branch Locator', 'बीजीबी शाखा लोकेटर', 'bi-bank2', 'bg-danger-subtle text-danger', 'Bihar Gramin Bank (PNB)', 'बिहार ग्रामीण बैंक (पीएनबी)', 'bgb.bank.in', 'https://bgb.bank.in/branch-locator?region=CHAPRA', 22, 'ACTIVE', NOW(), NOW())
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `url` = VALUES(`url`), `status` = 'ACTIVE';

-- ---------------------------------------------------------------------
-- Step 2: Global fast update for visibility and source tracking
-- ---------------------------------------------------------------------
UPDATE `listings`
SET 
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22
WHERE `source` = 'bgb.bank.in' OR `title` LIKE 'Bihar Gramin Bank%';

-- ---------------------------------------------------------------------
-- Step 3: Branch-by-branch description updates (clean without contact info)
-- ---------------------------------------------------------------------
-- Branch #1: Bihar Gramin Bank - Afour Branch (SOL ID: 5048 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Afour Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Afour Branch\n• Branch Code / SOL ID: 5048\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Nagra\n• Postal PIN Code: 841414\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-afour-branch' OR (`title` = 'Bihar Gramin Bank - Afour Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #2: Bihar Gramin Bank - Aloni Branch (SOL ID: 4598 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Aloni Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Aloni Branch\n• Branch Code / SOL ID: 4598\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Isuwapur\n• Postal PIN Code: 841411\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-aloni-branch' OR (`title` = 'Bihar Gramin Bank - Aloni Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #3: Bihar Gramin Bank - Amnaour Branch (SOL ID: 5025 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Amnaour Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Amnaour Branch\n• Branch Code / SOL ID: 5025\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Amnour\n• Postal PIN Code: 841301\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-amnaour-branch' OR (`title` = 'Bihar Gramin Bank - Amnaour Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #4: Bihar Gramin Bank - Bahadurpur Branch (SOL ID: 4583 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Bahadurpur Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Bahadurpur Branch\n• Branch Code / SOL ID: 4583\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Dariyapur\n• Postal PIN Code: 841232\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-bahadurpur-branch' OR (`title` = 'Bihar Gramin Bank - Bahadurpur Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #5: Bihar Gramin Bank - Baijalpur Branch (SOL ID: 4603 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Baijalpur Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Baijalpur Branch\n• Branch Code / SOL ID: 4603\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Sonpur\n• Postal PIN Code: 841101\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-baijalpur-branch' OR (`title` = 'Bihar Gramin Bank - Baijalpur Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #6: Bihar Gramin Bank - Balia Branch (SOL ID: 4601 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Balia Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Balia Branch\n• Branch Code / SOL ID: 4601\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Chapra\n• Postal PIN Code: 841211\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-balia-branch' OR (`title` = 'Bihar Gramin Bank - Balia Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #7: Bihar Gramin Bank - Banauta Branch (SOL ID: 4586 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Banauta Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Banauta Branch\n• Branch Code / SOL ID: 4586\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Parsa\n• Postal PIN Code: 841215\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-banauta-branch' OR (`title` = 'Bihar Gramin Bank - Banauta Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #8: Bihar Gramin Bank - Baniyarpur Branch (SOL ID: 5029 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Baniyarpur Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Baniyarpur Branch\n• Branch Code / SOL ID: 5029\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Baniyapur\n• Postal PIN Code: 841301\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-baniyarpur-branch' OR (`title` = 'Bihar Gramin Bank - Baniyarpur Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #9: Bihar Gramin Bank - Bankerwa Branch (SOL ID: 4566 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Bankerwa Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Bankerwa Branch\n• Branch Code / SOL ID: 4566\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Isuwapur\n• Postal PIN Code: 841215\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-bankerwa-branch' OR (`title` = 'Bihar Gramin Bank - Bankerwa Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #10: Bihar Gramin Bank - Bansohi Branch (SOL ID: 4573 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Bansohi Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Bansohi Branch\n• Branch Code / SOL ID: 4573\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Sonpur\n• Postal PIN Code: 841217\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-bansohi-branch' OR (`title` = 'Bihar Gramin Bank - Bansohi Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #11: Bihar Gramin Bank - Barka Bania Branch (SOL ID: 4564 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Barka Bania Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Barka Bania Branch\n• Branch Code / SOL ID: 4564\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Parsa\n• Postal PIN Code: 841219\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-barka-bania-branch' OR (`title` = 'Bihar Gramin Bank - Barka Bania Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #12: Bihar Gramin Bank - Basahi Branch (SOL ID: 4597 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Basahi Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Basahi Branch\n• Branch Code / SOL ID: 4597\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Panapur\n• Postal PIN Code: 841311\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-basahi-branch' OR (`title` = 'Bihar Gramin Bank - Basahi Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #13: Bihar Gramin Bank - Basantpur Bangla Branch (SOL ID: 4592 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Basantpur Bangla Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Basantpur Bangla Branch\n• Branch Code / SOL ID: 4592\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Amnour\n• Postal PIN Code: 841401\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-basantpur-bangla-branch' OR (`title` = 'Bihar Gramin Bank - Basantpur Bangla Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #14: Bihar Gramin Bank - Benaut Branch (SOL ID: 4577 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Benaut Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Benaut Branch\n• Branch Code / SOL ID: 4577\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Ekma\n• Postal PIN Code: 841204\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-benaut-branch' OR (`title` = 'Bihar Gramin Bank - Benaut Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #15: Bihar Gramin Bank - Bheldi Branch (SOL ID: 4558 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Bheldi Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Bheldi Branch\n• Branch Code / SOL ID: 4558\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Parsa\n• Postal PIN Code: 841402\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-bheldi-branch' OR (`title` = 'Bihar Gramin Bank - Bheldi Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #16: Bihar Gramin Bank - Bhithi Branch (SOL ID: 4599 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Bhithi Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Bhithi Branch\n• Branch Code / SOL ID: 4599\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Rivilganj\n• Postal PIN Code: 841214\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-bhithi-branch' OR (`title` = 'Bihar Gramin Bank - Bhithi Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #17: Bihar Gramin Bank - Bishunpura Branch (SOL ID: 4602 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Bishunpura Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Bishunpura Branch\n• Branch Code / SOL ID: 4602\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Sonpur\n• Postal PIN Code: 841101\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-bishunpura-branch' OR (`title` = 'Bihar Gramin Bank - Bishunpura Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #18: Bihar Gramin Bank - Brahampur Branch (SOL ID: 4593 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Brahampur Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Brahampur Branch\n• Branch Code / SOL ID: 4593\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Chapra\n• Postal PIN Code: 847409\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-brahampur-branch' OR (`title` = 'Bihar Gramin Bank - Brahampur Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #19: Bihar Gramin Bank - Chapra Branch (SOL ID: 4551 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Chapra Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Chapra Branch\n• Branch Code / SOL ID: 4551\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Chapra\n• Postal PIN Code: 841301\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-chapra-branch' OR (`title` = 'Bihar Gramin Bank - Chapra Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #20: Bihar Gramin Bank - Chhapia Branch (SOL ID: 4612 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Chhapia Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Chhapia Branch\n• Branch Code / SOL ID: 4612\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Mashrakh\n• Postal PIN Code: 841417\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-chhapia-branch' OR (`title` = 'Bihar Gramin Bank - Chhapia Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #21: Bihar Gramin Bank - Chitraulia Branch (SOL ID: 4581 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Chitraulia Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Chitraulia Branch\n• Branch Code / SOL ID: 4581\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Chapra\n• Postal PIN Code: 841208\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-chitraulia-branch' OR (`title` = 'Bihar Gramin Bank - Chitraulia Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #22: Bihar Gramin Bank - Darihara Branch (SOL ID: 4574 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Darihara Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Darihara Branch\n• Branch Code / SOL ID: 4574\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Dariyapur\n• Postal PIN Code: 841101\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-darihara-branch' OR (`title` = 'Bihar Gramin Bank - Darihara Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #23: Bihar Gramin Bank - Daudpur Branch (SOL ID: 4553 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Daudpur Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Daudpur Branch\n• Branch Code / SOL ID: 4553\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Manjhi\n• Postal PIN Code: 841205\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-daudpur-branch' OR (`title` = 'Bihar Gramin Bank - Daudpur Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #24: Bihar Gramin Bank - Dhanaura Branch (SOL ID: 4572 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Dhanaura Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Dhanaura Branch\n• Branch Code / SOL ID: 4572\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Garkha\n• Postal PIN Code: 841216\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-dhanaura-branch' OR (`title` = 'Bihar Gramin Bank - Dhanaura Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #25: Bihar Gramin Bank - Dhawri Gopal Branch (SOL ID: 4580 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Dhawri Gopal Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Dhawri Gopal Branch\n• Branch Code / SOL ID: 4580\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Baniyapur\n• Postal PIN Code: 841422\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-dhawri-gopal-branch' OR (`title` = 'Bihar Gramin Bank - Dhawri Gopal Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #26: Bihar Gramin Bank - Doriganj Branch (SOL ID: 5019 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Doriganj Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Doriganj Branch\n• Branch Code / SOL ID: 5019\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Chapra\n• Postal PIN Code: 841301\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-doriganj-branch' OR (`title` = 'Bihar Gramin Bank - Doriganj Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #27: Bihar Gramin Bank - Garkha Branch (SOL ID: 4614 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Garkha Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Garkha Branch\n• Branch Code / SOL ID: 4614\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Garkha\n• Postal PIN Code: 841414\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-garkha-branch' OR (`title` = 'Bihar Gramin Bank - Garkha Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #28: Bihar Gramin Bank - Gaur Bazar Branch (SOL ID: 4567 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Gaur Bazar Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Gaur Bazar Branch\n• Branch Code / SOL ID: 4567\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Madhaurah\n• Postal PIN Code: 841413\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-gaur-bazar-branch' OR (`title` = 'Bihar Gramin Bank - Gaur Bazar Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #29: Bihar Gramin Bank - Gobind Chak Branch (SOL ID: 4604 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Gobind Chak Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Gobind Chak Branch\n• Branch Code / SOL ID: 4604\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Jalalpur\n• Postal PIN Code: 841412\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-gobind-chak-branch' OR (`title` = 'Bihar Gramin Bank - Gobind Chak Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #30: Bihar Gramin Bank - Harpur Branch (SOL ID: 4559 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Harpur Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Harpur Branch\n• Branch Code / SOL ID: 4559\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Dariyapur\n• Postal PIN Code: 841403\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-harpur-branch' OR (`title` = 'Bihar Gramin Bank - Harpur Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #31: Bihar Gramin Bank - Inai Branch (SOL ID: 4594 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Inai Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Inai Branch\n• Branch Code / SOL ID: 4594\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Rivilganj\n• Postal PIN Code: 841209\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-inai-branch' OR (`title` = 'Bihar Gramin Bank - Inai Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #32: Bihar Gramin Bank - Isuapur Branch (SOL ID: 4561 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Isuapur Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Isuapur Branch\n• Branch Code / SOL ID: 4561\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Isuwapur\n• Postal PIN Code: 841411\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-isuapur-branch' OR (`title` = 'Bihar Gramin Bank - Isuapur Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #33: Bihar Gramin Bank - Jalalpur Branch (SOL ID: 4587 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Jalalpur Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Jalalpur Branch\n• Branch Code / SOL ID: 4587\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Jalalpur\n• Postal PIN Code: 841412\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-jalalpur-branch' OR (`title` = 'Bihar Gramin Bank - Jalalpur Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #34: Bihar Gramin Bank - Jhowan Branch (SOL ID: 4610 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Jhowan Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Jhowan Branch\n• Branch Code / SOL ID: 4610\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Chapra\n• Postal PIN Code: 841301\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-jhowan-branch' OR (`title` = 'Bihar Gramin Bank - Jhowan Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #35: Bihar Gramin Bank - Kamta Branch (SOL ID: 4554 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Kamta Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Kamta Branch\n• Branch Code / SOL ID: 4554\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Baniyapur\n• Postal PIN Code: 841403\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-kamta-branch' OR (`title` = 'Bihar Gramin Bank - Kamta Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #36: Bihar Gramin Bank - Kanhauli Manohar Branch (SOL ID: 5125 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Kanhauli Manohar Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Kanhauli Manohar Branch\n• Branch Code / SOL ID: 5125\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Baniyapur\n• Postal PIN Code: 841403\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-kanhauli-manohar-branch' OR (`title` = 'Bihar Gramin Bank - Kanhauli Manohar Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #37: Bihar Gramin Bank - Karanpura Branch (SOL ID: 4607 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Karanpura Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Karanpura Branch\n• Branch Code / SOL ID: 4607\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Mashrakh\n• Postal PIN Code: 841417\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-karanpura-branch' OR (`title` = 'Bihar Gramin Bank - Karanpura Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #38: Bihar Gramin Bank - Katsa Branch (SOL ID: 4570 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Katsa Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Katsa Branch\n• Branch Code / SOL ID: 4570\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Amnour\n• Postal PIN Code: 841311\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-katsa-branch' OR (`title` = 'Bihar Gramin Bank - Katsa Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #39: Bihar Gramin Bank - Khaira Branch (SOL ID: 4556 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Khaira Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Khaira Branch\n• Branch Code / SOL ID: 4556\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Nagra\n• Postal PIN Code: 841414\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-khaira-branch' OR (`title` = 'Bihar Gramin Bank - Khaira Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #40: Bihar Gramin Bank - Khakhi Mathia Branch (SOL ID: 4611 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Khakhi Mathia Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Khakhi Mathia Branch\n• Branch Code / SOL ID: 4611\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Baniyapur\n• Postal PIN Code: 841403\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-khakhi-mathia-branch' OR (`title` = 'Bihar Gramin Bank - Khakhi Mathia Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #41: Bihar Gramin Bank - Kohara Branch (SOL ID: 4600 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Kohara Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Kohara Branch\n• Branch Code / SOL ID: 4600\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Madhaurah\n• Postal PIN Code: 841415\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-kohara-branch' OR (`title` = 'Bihar Gramin Bank - Kohara Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #42: Bihar Gramin Bank - Konh Bhagwanpur Branch (SOL ID: 4576 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Konh Bhagwanpur Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Konh Bhagwanpur Branch\n• Branch Code / SOL ID: 4576\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Ekma\n• Postal PIN Code: 841204\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-konh-bhagwanpur-branch' OR (`title` = 'Bihar Gramin Bank - Konh Bhagwanpur Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #43: Bihar Gramin Bank - Kopa Branch (SOL ID: 4557 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Kopa Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Kopa Branch\n• Branch Code / SOL ID: 4557\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Madhaurah\n• Postal PIN Code: 841415\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-kopa-branch' OR (`title` = 'Bihar Gramin Bank - Kopa Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #44: Bihar Gramin Bank - Kothia Branch (SOL ID: 5047 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Kothia Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Kothia Branch\n• Branch Code / SOL ID: 5047\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Garkha\n• Postal PIN Code: 841311\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-kothia-branch' OR (`title` = 'Bihar Gramin Bank - Kothia Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #45: Bihar Gramin Bank - Lahladpur Branch (SOL ID: 4905 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Lahladpur Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Lahladpur Branch\n• Branch Code / SOL ID: 4905\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Lahladpur\n• Postal PIN Code: 841224\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-lahladpur-branch' OR (`title` = 'Bihar Gramin Bank - Lahladpur Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #46: Bihar Gramin Bank - Latrahiya Branch (SOL ID: 5046 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Latrahiya Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Latrahiya Branch\n• Branch Code / SOL ID: 5046\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Parsa\n• Postal PIN Code: 841219\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-latrahiya-branch' OR (`title` = 'Bihar Gramin Bank - Latrahiya Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #47: Bihar Gramin Bank - Mahmada Branch (SOL ID: 4590 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Mahmada Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Mahmada Branch\n• Branch Code / SOL ID: 4590\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Garkha\n• Postal PIN Code: 841301\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-mahmada-branch' OR (`title` = 'Bihar Gramin Bank - Mahmada Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #48: Bihar Gramin Bank - Maker Branch (SOL ID: 5126 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Maker Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Maker Branch\n• Branch Code / SOL ID: 5126\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Maker\n• Postal PIN Code: 841215\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-maker-branch' OR (`title` = 'Bihar Gramin Bank - Maker Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #49: Bihar Gramin Bank - Mangarpal Murtuza Branch (SOL ID: 4588 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Mangarpal Murtuza Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Mangarpal Murtuza Branch\n• Branch Code / SOL ID: 4588\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Sonpur\n• Postal PIN Code: 841101\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-mangarpal-murtuza-branch' OR (`title` = 'Bihar Gramin Bank - Mangarpal Murtuza Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #50: Bihar Gramin Bank - Manjhi Branch (SOL ID: 5043 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Manjhi Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Manjhi Branch\n• Branch Code / SOL ID: 5043\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Manjhi\n• Postal PIN Code: 841313\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-manjhi-branch' OR (`title` = 'Bihar Gramin Bank - Manjhi Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #51: Bihar Gramin Bank - Marhowrah Branch (SOL ID: 5042 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Marhowrah Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Marhowrah Branch\n• Branch Code / SOL ID: 5042\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Madhaurah\n• Postal PIN Code: 841418\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-marhowrah-branch' OR (`title` = 'Bihar Gramin Bank - Marhowrah Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #52: Bihar Gramin Bank - Matiar Tajpur Branch (SOL ID: 4595 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Matiar Tajpur Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Matiar Tajpur Branch\n• Branch Code / SOL ID: 4595\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Manjhi\n• Postal PIN Code: 841403\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-matiar-tajpur-branch' OR (`title` = 'Bihar Gramin Bank - Matiar Tajpur Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #53: Bihar Gramin Bank - Mira Musehari Branch (SOL ID: 4591 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Mira Musehari Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Mira Musehari Branch\n• Branch Code / SOL ID: 4591\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Nagra\n• Postal PIN Code: 841414\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-mira-musehari-branch' OR (`title` = 'Bihar Gramin Bank - Mira Musehari Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #54: Bihar Gramin Bank - Mubarakpur Branch (SOL ID: 4578 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Mubarakpur Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Mubarakpur Branch\n• Branch Code / SOL ID: 4578\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Madhaurah\n• Postal PIN Code: 841416\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-mubarakpur-branch' OR (`title` = 'Bihar Gramin Bank - Mubarakpur Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #55: Bihar Gramin Bank - Nagara Branch (SOL ID: 4871 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Nagara Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Nagara Branch\n• Branch Code / SOL ID: 4871\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Nagra\n• Postal PIN Code: 841442\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-nagara-branch' OR (`title` = 'Bihar Gramin Bank - Nagara Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #56: Bihar Gramin Bank - Naini Branch (SOL ID: 4582 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Naini Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Naini Branch\n• Branch Code / SOL ID: 4582\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Chapra\n• Postal PIN Code: 841316\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-naini-branch' OR (`title` = 'Bihar Gramin Bank - Naini Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #57: Bihar Gramin Bank - Nandanpur Branch (SOL ID: 4569 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Nandanpur Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Nandanpur Branch\n• Branch Code / SOL ID: 4569\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Chapra\n• Postal PIN Code: 841430\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-nandanpur-branch' OR (`title` = 'Bihar Gramin Bank - Nandanpur Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #58: Bihar Gramin Bank - Naya Gaon Branch (SOL ID: 5045 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Naya Gaon Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Naya Gaon Branch\n• Branch Code / SOL ID: 5045\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Sonpur\n• Postal PIN Code: 841217\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-naya-gaon-branch' OR (`title` = 'Bihar Gramin Bank - Naya Gaon Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #59: Bihar Gramin Bank - Paiga Bazar Branch (SOL ID: 4562 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Paiga Bazar Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Paiga Bazar Branch\n• Branch Code / SOL ID: 4562\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Parsa\n• Postal PIN Code: 841402\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-paiga-bazar-branch' OR (`title` = 'Bihar Gramin Bank - Paiga Bazar Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #60: Bihar Gramin Bank - Parsa Branch (SOL ID: 5018 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Parsa Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Parsa Branch\n• Branch Code / SOL ID: 5018\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Parsa\n• Postal PIN Code: 841301\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-parsa-branch' OR (`title` = 'Bihar Gramin Bank - Parsa Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #61: Bihar Gramin Bank - Parsotimpur Branch (SOL ID: 4585 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Parsotimpur Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Parsotimpur Branch\n• Branch Code / SOL ID: 4585\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Lahladpur\n• Postal PIN Code: 841224\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-parsotimpur-branch' OR (`title` = 'Bihar Gramin Bank - Parsotimpur Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #62: Bihar Gramin Bank - Paterha Branch (SOL ID: 4606 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Paterha Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Paterha Branch\n• Branch Code / SOL ID: 4606\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Madhaurah\n• Postal PIN Code: 841418\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-paterha-branch' OR (`title` = 'Bihar Gramin Bank - Paterha Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #63: Bihar Gramin Bank - Pratappur Branch (SOL ID: 4563 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Pratappur Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Pratappur Branch\n• Branch Code / SOL ID: 4563\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Dariyapur\n• Postal PIN Code: 497223\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-pratappur-branch' OR (`title` = 'Bihar Gramin Bank - Pratappur Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #64: Bihar Gramin Bank - Puchhari Bazar Branch (SOL ID: 4560 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Puchhari Bazar Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Puchhari Bazar Branch\n• Branch Code / SOL ID: 4560\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Baniyapur\n• Postal PIN Code: 841403\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-puchhari-bazar-branch' OR (`title` = 'Bihar Gramin Bank - Puchhari Bazar Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #65: Bihar Gramin Bank - Raja Patti Branch (SOL ID: 4568 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Raja Patti Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Raja Patti Branch\n• Branch Code / SOL ID: 4568\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Mashrakh\n• Postal PIN Code: 841420\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-raja-patti-branch' OR (`title` = 'Bihar Gramin Bank - Raja Patti Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #66: Bihar Gramin Bank - Rampurkalla Branch (SOL ID: 4615 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Rampurkalla Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Rampurkalla Branch\n• Branch Code / SOL ID: 4615\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Garkha\n• Postal PIN Code: 841311\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-rampurkalla-branch' OR (`title` = 'Bihar Gramin Bank - Rampurkalla Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #67: Bihar Gramin Bank - Rasulpur Branch (SOL ID: 4565 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Rasulpur Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Rasulpur Branch\n• Branch Code / SOL ID: 4565\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Ekma\n• Postal PIN Code: 841402\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-rasulpur-branch' OR (`title` = 'Bihar Gramin Bank - Rasulpur Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #68: Bihar Gramin Bank - Rasulpur Chatti Branch (SOL ID: 4571 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Rasulpur Chatti Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Rasulpur Chatti Branch\n• Branch Code / SOL ID: 4571\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Ekma\n• Postal PIN Code: 841204\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-rasulpur-chatti-branch' OR (`title` = 'Bihar Gramin Bank - Rasulpur Chatti Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #69: Bihar Gramin Bank - Sahajitpur Branch (SOL ID: 4904 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Sahajitpur Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Sahajitpur Branch\n• Branch Code / SOL ID: 4904\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Baniyapur\n• Postal PIN Code: 841422\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-sahajitpur-branch' OR (`title` = 'Bihar Gramin Bank - Sahajitpur Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #70: Bihar Gramin Bank - Sakardih Branch (SOL ID: 4605 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Sakardih Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Sakardih Branch\n• Branch Code / SOL ID: 4605\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Nagra\n• Postal PIN Code: 841414\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-sakardih-branch' OR (`title` = 'Bihar Gramin Bank - Sakardih Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #71: Bihar Gramin Bank - Sakhnauli Bharahpur Branch (SOL ID: 4608 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Sakhnauli Bharahpur Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Sakhnauli Bharahpur Branch\n• Branch Code / SOL ID: 4608\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Dighwara\n• Postal PIN Code: 841207\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-sakhnauli-bharahpur-branch' OR (`title` = 'Bihar Gramin Bank - Sakhnauli Bharahpur Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #72: Bihar Gramin Bank - Saraiya Branch (SOL ID: 4584 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Saraiya Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Saraiya Branch\n• Branch Code / SOL ID: 4584\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Baniyapur\n• Postal PIN Code: 841403\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-saraiya-branch' OR (`title` = 'Bihar Gramin Bank - Saraiya Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #73: Bihar Gramin Bank - Sarhwara Branch (SOL ID: 4613 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Sarhwara Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Sarhwara Branch\n• Branch Code / SOL ID: 4613\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Isuwapur\n• Postal PIN Code: 841421\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-sarhwara-branch' OR (`title` = 'Bihar Gramin Bank - Sarhwara Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #74: Bihar Gramin Bank - Satjora Branch (SOL ID: 4575 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Satjora Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Satjora Branch\n• Branch Code / SOL ID: 4575\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Panapur\n• Postal PIN Code: 841410\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-satjora-branch' OR (`title` = 'Bihar Gramin Bank - Satjora Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #75: Bihar Gramin Bank - Satuan Branch (SOL ID: 4596 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Satuan Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Satuan Branch\n• Branch Code / SOL ID: 4596\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Baniyapur\n• Postal PIN Code: 841206\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-satuan-branch' OR (`title` = 'Bihar Gramin Bank - Satuan Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #76: Bihar Gramin Bank - Shikarpur Branch (SOL ID: 4589 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Shikarpur Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Shikarpur Branch\n• Branch Code / SOL ID: 4589\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Sonpur\n• Postal PIN Code: 845105\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-shikarpur-branch' OR (`title` = 'Bihar Gramin Bank - Shikarpur Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #77: Bihar Gramin Bank - Shitalpur I Branch (SOL ID: 4579 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Shitalpur I Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Shitalpur I Branch\n• Branch Code / SOL ID: 4579\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Dighwara\n• Postal PIN Code: 841208\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-shitalpur-i-branch' OR (`title` = 'Bihar Gramin Bank - Shitalpur I Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #78: Bihar Gramin Bank - Shitalpur Ii Branch (SOL ID: 5044 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Shitalpur Ii Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Shitalpur Ii Branch\n• Branch Code / SOL ID: 5044\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Dighwara\n• Postal PIN Code: 841221\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-shitalpur-ii-branch' OR (`title` = 'Bihar Gramin Bank - Shitalpur Ii Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #79: Bihar Gramin Bank - Sonho Bazar Branch (SOL ID: 4555 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Sonho Bazar Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Sonho Bazar Branch\n• Branch Code / SOL ID: 4555\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Maker\n• Postal PIN Code: 841460\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-sonho-bazar-branch' OR (`title` = 'Bihar Gramin Bank - Sonho Bazar Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

-- Branch #80: Bihar Gramin Bank - Turki Panapur Branch (SOL ID: 4609 | IFSC: PUNB0MBGB06)
UPDATE `listings` SET
    `mobile_visibility` = 'REGISTERED',
    `email_visibility` = 'REGISTERED',
    `source` = 'bgb.bank.in',
    `source_id` = 22,
    `description` = 'Bihar Gramin Bank (BGB) - Turki Panapur Branch is an operational Regional Rural Bank branch operating under Regional Office Chapra, Saran District, Bihar. Sponsored by Punjab National Bank (PNB).\n\n• Bank Name: Bihar Gramin Bank (BGB)\n• Branch Name: Turki Panapur Branch\n• Branch Code / SOL ID: 4609\n• IFSC Code: PUNB0MBGB06\n• Regional Office: Chapra (SARAN)\n• Block / Administrative Area: Panapur\n• Postal PIN Code: 841216\n• Official Reference: https://bgb.bank.in\n• Key Banking Services: Savings Accounts, Current Accounts, Kisan Credit Card (KCC) Agriculture Loans, Direct Benefit Transfer (DBT), SHG Loans, Mudra Financing, Term & Recurring Deposits, RTGS / NEFT / IMPS Fund Transfers, and Rupay ATM Services.'
WHERE `slug` = 'bihar-gramin-bank-turki-panapur-branch' OR (`title` = 'Bihar Gramin Bank - Turki Panapur Branch' AND (`source` = 'bgb.bank.in' OR `category_id` = 9));

