<?php
return [
    'MUTATION' => [
        'Required' => [
            'affidavits' => [
                'label' => 'Affidavits',
                'id' => 'affidavits',
                'multiple' => true,
                'inputs' => [
                    'affidavitsDateOfAttestation' => [
                        'label' => 'Date of attestation',
                        'type' => 'date',
                        'id' => 'affidavitsDateOfAttestation'
                    ],
                    'affidavitAttestedBy' => [
                        'label' => 'Attested by',
                        'type' => 'text',
                        'id' => 'affidavitAttestedBy'
                    ],
                ]
            ],
            'indemnity_bond' => [
                'label' => 'Indemnity Bond',
                'id' => 'indemnityBond',
                'multiple' => true,
                'inputs' => [
                    'indemnityBondDateOfAttestation' =>  [
                        'label' => 'Date of attestation',
                        'type' => 'date',
                        'id' => 'indemnityBondDateOfAttestation'
                    ],
                    'indemnityBondAttestedBy' =>  [
                        'label' => 'Attested by',
                        'type' => 'text',
                        'id' => 'indemnityBondAttestedBy'
                    ],
                ]
            ],
            'lease_conveyance_deed' => [
                'label' => 'Lease Deed/Conveyance Deed',
                'id' => 'leaseconyedeed',
                'multiple' => false,
                'inputs' => [
                    // 'leaseConvDeedDateOfExecution' => [
                    //     'label' => 'Date of execution',
                    //     'type' => 'date',
                    //     'id' => 'leaseConvDeedDateOfExecution'
                    // ],
                    // 'leaseConvDeedLesseename' => [
                    //     'label' => 'Lessee Name',
                    //     'type' => 'text',
                    //     'id' => 'leaseConvDeedLesseename'
                    // ],
                ]
            ],
            'pan_number' => [
                'label' => 'Upload PAN of Registered Applicant',
                'id' => 'panNumber',
                'multiple' => false,
                'inputs' => [
                    // 'panCertificateNo' => [
                    //     'label' => 'PAN Number',
                    //     'type' => 'text',
                    //     'id' => 'panCertificateNo'
                    // ]
                ]
            ],
            'aadhar_number' => [
                'label' => 'Upload Aadhaar of Registered Applicant',
                'id' => 'aadharnumber',
                'multiple' => false,
                'inputs' => [
                    // 'aadharCertificateNo' => [
                    //     'label' => 'Aadhar Number',
                    //     'type' => 'text',
                    //     'id' => 'aadharCertificateNo'
                    // ]
                ]
            ],
            'public_notice_english' => [
                'label' => 'Public Notice in National Daily (English)',
                'id' => 'publicNoticeEnglish',
                'multiple' => false,
                'inputs' => [
                    'newspaperNameEnglish' => [
                        'label' => 'Name of Newspaper (English)',
                        'type' => 'text',
                        'id' => 'newspaperNameEnglish'
                    ],
                    'publicNoticeDateEnglish' => [
                        'label' => 'Date of Public Notice',
                        'type' => 'date',
                        'id' => 'publicNoticeDateEnglish'
                    ],
                ]
            ],
            'public_notice_hindi' => [
                'label' => 'Public Notice in National Daily (Hindi)',
                'id' => 'publicNoticeHindi',
                'multiple' => false,
                'inputs' => [
                    'newspaperNameHindi' => [
                        'label' => 'Name of Newspaper (Hindi)',
                        'type' => 'text',
                        'id' => 'newspaperNameHindi'
                    ],
                    'publicNoticeDateHindi' => [
                        'label' => 'Date of Public Notice',
                        'type' => 'date',
                        'id' => 'publicNoticeDateHindi'
                    ],
                ]
            ],
            'property_photo' => [
                'label' => 'Property Photo',
                'id' => 'propertyPhoto',
                'multiple' => false,
                'inputs' => []
            ]
        ],
        'Optional' => [
            'death_certificate' => [
                'label' => 'Death Certificate',
                'id' => 'deathCertificate',
                'multiple' => false,
                'inputs' => [
                    'deathCertificateDeceasedName' => [
                        'label' => 'Name of Deceased',
                        'type' => 'text',
                        'id' => 'deathCertificateDeceasedName'
                    ],
                    'deathCertificateDeathdate' => [
                        'label' => 'Date of Death',
                        'type' => 'date',
                        'id' => 'deathCertificateDeathdate'
                    ],
                    'deathCertificateIssuedate' => [
                        'label' => 'Date of Issue',
                        'type' => 'date',
                        'id' => 'deathCertificateIssuedate'
                    ],
                    'deathCertificateDocumentCertificate' => [
                        'label' => 'Document/Certificate No.',
                        'type' => 'text',
                        'id' => 'deathCertificateDocumentCertificate'
                    ]

                ]
            ],
            'sale_deed' => [
                'label' => 'Sale Deed',
                'id' => 'saleDeed',
                'multiple' => false,
                'inputs' => [
                    'saleDeedRegno' => [
                        'label' => 'Registration No.',
                        'type' => 'text',
                        'id' => 'saleDeedRegno'
                    ],
                    'saleDeedVolume' => [
                        'label' => 'Volume',
                        'type' => 'text',
                        'id' => 'saleDeedVolume'
                    ],
                    'saleDeedBookNo' => [
                        'label' => 'Book No.',
                        'type' => 'text',
                        'id' => 'saleDeedBookNo'
                    ],
                    'saleDeedFrom' => [
                        'label' => 'From',
                        'type' => 'text',
                        'id' => 'saleDeedFrom'
                    ],
                    'saleDeedTo' => [
                        'label' => 'To',
                        'type' => 'text',
                        'id' => 'saleDeedTo'
                    ],
                    'saleDeedRegDate' => [
                        'label' => 'Regn. Date',
                        'type' => 'date',
                        'id' => 'saleDeedRegDate'
                    ],
                    'saleDeedRegOfficeName' => [
                        'label' => 'Registration Office Name',
                        'type' => 'text',
                        'id' => 'saleDeedRegOfficeName'
                    ]
                ]
            ],
            'regd_will_deed' => [
                'label' => 'Regd. Will/Codicil',
                'id' => 'regdWillDeed',
                'multiple' => false,
                'inputs' => [
                    'regWillDeedTestatorName' => [
                        'label' => 'Name of Testator',
                        'type' => 'text',
                        'id' => 'regWillDeedTestatorName'
                    ],
                    'regWillDeedRegNo' => [
                        'label' => 'Registration No.',
                        'type' => 'text',
                        'id' => 'regWillDeedRegNo'
                    ],
                    'regWillDeedVolume' => [
                        'label' => 'Volume',
                        'type' => 'text',
                        'id' => 'regWillDeedVolume'
                    ],
                    'regWillDeedBookNo' => [
                        'label' => 'Book No.',
                        'type' => 'text',
                        'id' => 'regWillDeedBookNo'
                    ],
                    'regWillDeedFrom' => [
                        'label' => 'From',
                        'type' => 'text',
                        'id' => 'regWillDeedFrom'
                    ],
                    'regWillDeedTo' => [
                        'label' => 'To',
                        'type' => 'text',
                        'id' => 'regWillDeedTo'
                    ],
                    'regWillDeedRegDate' => [
                        'label' => 'Regn. Date',
                        'type' => 'date',
                        'id' => 'regWillDeedRegDate'
                    ],
                    'regWillDeedRegOfficeName' => [
                        'label' => 'Registration Office Name',
                        'type' => 'text',
                        'id' => 'regWillDeedRegOfficeName'
                    ]
                ]
            ],
            'unregd_will_codocil' => [
                'label' => 'Unregd. Will/Codicil',
                'id' => 'unregdWillCodocil',
                'multiple' => false,
                'inputs' => [
                    'unregWillCodicilTestatorName' => [
                        'label' => 'Name of Testator',
                        'type' => 'text',
                        'id' => 'unregWillCodicilTestatorName'
                    ],
                    'unregWillCodicilDateOfWillCodicil' => [
                        'label' => 'Date of Will/Codicil',
                        'type' => 'date',
                        'id' => 'unregWillCodicilDateOfWillCodicil'
                    ]
                ]
            ],
            'relinquishment_deed' => [
                'label' => 'Registered Relinquishment Deed',
                'id' => 'relinquishmentDeed',
                'multiple' => false,
                'inputs' => [
                    'relinquishDeedReleaserName' => [
                        'label' => 'Name of Releaser',
                        'type' => 'text',
                        'id' => 'relinquishDeedReleaserName'
                    ],
                    'relinquishDeedRegNo' => [
                        'label' => 'Registration No.',
                        'type' => 'text',
                        'id' => 'relinquishDeedRegNo'
                    ],
                    'relinquishDeedVolume' => [
                        'label' => 'Volume',
                        'type' => 'text',
                        'id' => 'relinquishDeedVolume'
                    ],
                    'relinquishDeedBookno' => [
                        'label' => 'Book No.',
                        'type' => 'text',
                        'id' => 'relinquishDeedBookno'
                    ],
                    'relinquishDeedFrom' => [
                        'label' => 'From',
                        'type' => 'text',
                        'id' => 'relinquishDeedFrom'
                    ],
                    'relinquishDeedTo' => [
                        'label' => 'To',
                        'type' => 'text',
                        'id' => 'relinquishDeedTo'
                    ],
                    'relinquishDeedRegdate' => [
                        'label' => 'Regn. Date',
                        'type' => 'date',
                        'id' => 'relinquishDeedRegdate'
                    ],
                    'relinquishDeedRegname' => [
                        'label' => 'Registration office name',
                        'type' => 'text',
                        'id' => 'relinquishDeedRegname'
                    ]
                ]
            ],
            'gift_deed' => [
                'label' => 'Gift Deed',
                'id' => 'giftDeed',
                'multiple' => false,
                'inputs' => [
                    'giftdeedRegno' => [
                        'label' => 'Registration No.',
                        'type' => 'text',
                        'id' => 'giftdeedRegno'
                    ],
                    'giftdeedVolume' => [
                        'label' => 'Volume',
                        'type' => 'text',
                        'id' => 'giftdeedVolume'
                    ],
                    'giftdeedBookno' => [
                        'label' => 'Book No.',
                        'type' => 'text',
                        'id' => 'giftdeedBookno'
                    ],
                    'giftdeedFrom' => [
                        'label' => 'From',
                        'type' => 'text',
                        'id' => 'giftdeedFrom'
                    ],
                    'giftdeedTo' => [
                        'label' => 'To',
                        'type' => 'text',
                        'id' => 'giftdeedTo'
                    ],
                    'giftdeedRegdate' => [
                        'label' => 'Regn. Date',
                        'type' => 'date',
                        'id' => 'giftdeedRegdate'
                    ],
                    'giftdeedRegOfficeName' => [
                        'label' => 'Registration office name',
                        'type' => 'text',
                        'id' => 'giftdeedRegOfficeName'
                    ]
                ]
            ],
            'surviving_member_certificate' => [
                'label' => 'Surviving Member Certificate(SMC)',
                'id' => 'survivingMemberCertificate',
                'multiple' => false,
                'inputs' => [
                    'smcCertificateNo' => [
                        'label' => 'Certificate No.',
                        'type' => 'text',
                        'id' => 'smcCertificateNo'
                    ],
                    'smcDateOfIssue' => [
                        'label' => 'Date of Issue',
                        'type' => 'date',
                        'id' => 'smcDateOfIssue'
                    ]
                ]
            ],
            'sanction_building_plan' => [
                'label' => 'Sanction Building Plan(SBP)',
                'id' => 'sanctionBuildingPlan',
                'multiple' => false,
                'inputs' => [
                    'sbpDateOfIssue' => [
                        'label' => 'Date of Issue',
                        'type' => 'date',
                        'id' => 'sbpDateOfIssue'
                    ]
                ]
            ],
            'any_other_document' => [
                'label' => 'Any Other Document',
                'id' => 'anyOtherDocument',
                'multiple' => false,
                'inputs' => [
                    'otherDocumentRemark' => [
                        'label' => 'Remarks',
                        'type' => 'textarea',
                        'id' => 'otherDocumentRemark'
                    ]
                ]
            ],
            'propate_loa_court_decree_order' => [
                'label' => 'Propate/LOA/Court Decree/Order',
                'id' => 'propateLoaCourtDecreeOrder',
                'multiple' => false,
                'inputs' => [
                    // 'leaseConvDeedDateOfExecution' => [
                    //     'label' => 'Date of execution',
                    //     'type' => 'date',
                    //     'id' => 'leaseConvDeedDateOfExecution'
                    // ],
                    // 'leaseConvDeedLesseename' => [
                    //     'label' => 'Lessee Name',
                    //     'type' => 'text',
                    //     'id' => 'leaseConvDeedLesseename'
                    // ],
                ]
            ],

        ],
        'TempModelName' => 'TempSubstitutionMutation'
    ],
    'LUC' => [
        'documents' => [
            'lucpropertyTaxpayreceipt' => [
                'id' => 'lucpropertyTaxpayreceipt',
                'label' => 'Property Tax Payment Receipt',
                'rowOrder' => 1,
                'required' => 1
            ],
            'PropertyTaxAssessmentReceipt' => [
                'id' => 'PropertyTaxAssessmentReceipt',
                'label' => 'Property Tax Assessment',
                'rowOrder' => 2,
                'required' => 1
            ],
            'lucphoto1' => [
                'id' => 'lucphoto1',
                'label' => 'Property Photo',
                'rowOrder' => 3,
                'required' => 1
            ],
            'lucphotooptional' => [
                'id' => 'lucphotooptional',
                'label' => 'Property Photo2',
                'rowOrder' => 3,
                'required' => 0
            ],
            'lucmpdzonalpermitting' => [
                'id' => 'lucmpdzonalpermitting',
                'label' => 'MPD/Zonal Plan Permitting LUC',
                'rowOrder' => 4,
                'required' => 1
            ]
        ],
        'TempModelName' => 'TempLandUseChangeApplication'
    ],
    'DOA' => [
        'documents' => [
            'BuilderBuyerAgreement' => [
                'id' => 'BuilderBuyerAgreement',
                'label' => 'Builder Buyer Agreement',
                'rowOrder' => 1,
                'required' => 1,
                'options' => [],
            ],
            'SaleDeed' => [
                'id' => 'SaleDeed',
                'label' => 'Sale Deed',
                'rowOrder' => 2,
                'required' => 1,
                'options' => [],
            ],
            'BuildingPlan' => [
                'id' => 'BuildingPlan',
                'label' => 'Building Plan',
                'rowOrder' => 3,
                'required' => 1,
                'options' => [],
            ],
            'OtherDocument' => [
                'id' => 'OtherDocument',
                'label' => 'Other Document',
                'rowOrder' => 4,
                'required' => 1,
                'options' => [],
            ]
        ],
        'TempModelName' => 'TempDeedOfApartment'
    ],
    'CONVERSION' => [
        'Required' => [
            'indemnity_bond' => [
                'label' => 'Indemnity Bond (Annexure-F)',
                'id' => 'convDocIndemnityBond',
                'multiple' => true,
                'inputs' => [
                    'convDocIndemnityBondDateOfAttestation' =>  [
                        'label' => 'Date of attestation',
                        'type' => 'date',
                        'id' => 'convDocIndemnityBondDateOfAttestation'
                    ],
                    'convDocIndemnityBondAttestedBy' =>  [
                        'label' => 'Attested by',
                        'type' => 'text',
                        'id' => 'convDocIndemnityBondAttestedBy'
                    ],
                ]
            ],
            'convUndertaking' => [
                'label' => 'Undertaking (Annexure-G)',
                'id' => 'convDocUndertaking',
                'multiple' => true,
                'inputs' => [
                    'convDocDateOfUndertaking' =>  [
                        'label' => 'Date of attestation',
                        'type' => 'date',
                        'id' => 'convDocDateOfUndertaking'
                    ],
                ]
            ],
            'convLastSubstitutionLetter' => [
                'label' => 'Self-attested copy of last Substitution/Mutation Letter',
                'id' => 'convDOcLastSubstitutionLetter',
                'multiple' => false,
                'inputs' => [
                    'convDocSubLetterDate' =>  [
                        'label' => 'Date of Document',
                        'type' => 'date',
                        'id' => 'convDocSubLetterDate'
                    ],
                ]
            ],
            'convProofOfConstruction' => [
                'label' => 'Upload file',
                'id' => 'convDocProofOfConstruction',
                'multiple' => false,
                'isFirstInput' => false,
                'displayAtIndex' => 1,
                'inputs' => [
                    'convDocContructionProofType' =>  [
                        'label' => 'Prooof of construction',
                        'type' => 'select',
                        'id' => 'convDocContructionProofType',
                        'options' => [
                            'C/D Form',
                            'Sanctioned Building Plan',
                            'Completion Certificate',
                            'Payment Receipt of Property Tax Preceding 2 Years',
                            'Other'
                        ]
                    ],
                    'convDocContructionProofDate' =>  [
                        'label' => 'Date of Document',
                        'type' => 'date',
                        'id' => 'convDocContructionProofDate',
                    ],
                    'convDocContructionProofIssuingAuthority' =>  [
                        'label' => 'Issuing Authority',
                        'type' => 'text',
                        'id' => 'convDocContructionProofIssuingAuthority',
                    ],
                ]
            ],
            'convProofOfPossession' => [
                'label' => 'Upload file',
                'id' => 'convDocProofOfPossession',
                'multiple' => false,
                'isFirstInput' => false,
                'displayAtIndex' => 1,
                'inputs' => [
                    'convDocPossessionProofType' =>  [
                        'label' => 'Prooof of possession',
                        'type' => 'select',
                        'id' => 'convDocPossessionProofType',
                        'options' => [
                            'Latest Electricity Bill',
                            'Latest IGL Bill',
                            'Latest Telephone Bill',
                            'Other'
                        ],
                    ],
                    'convDocPossessionProofDate' =>  [
                        'label' => 'Date of Document',
                        'type' => 'date',
                        'id' => 'convDocPossessionProofDate',
                    ],
                    'convDocPossessionProofIssuingAuthority' =>  [
                        'label' => 'Issuing Authority',
                        'type' => 'text',
                        'id' => 'convDocPossessionProofIssuingAuthority',
                    ],
                ]
            ],
            'convLeaseDeed' => [
                'label' => 'Registered Lease Deed',
                'id' => 'convDocLeaseDeed',
                'multiple' => false,
                'inputs' => [
                    'convDocLeaseDeedDoE' => [
                        'label' => 'Date of execution',
                        'type' => 'date',
                        'id' => 'convDocLeaseDeedDoE'
                    ],
                    /*  'leaseConvDeedLesseename' => [
                            'label' => 'Lessee Name',
                            'type' => 'text',
                            'id' => 'leaseConvDeedLesseename'
                        ], */
                ]
            ],
            'convApplicantAadhaar' => [
                'label' => 'Applicant Aadhaar',
                'id' => 'convDocApplicantAadhaar',
                'multiple' => false,
                'inputs' => [
                    // 'convApplicantAahaarNo' => [
                    //     'label' => 'Aadhaar Number',
                    //     'type' => 'text',
                    //     'id' => 'convApplicantAahaarNo'
                    // ]
                ] 
            ],
            'convApplicantPan' => [
                'label' => 'Applicant PAN',
                'id' => 'convDocApplicantPan',
                'multiple' => false,
                'inputs' => [
                    // 'aadharCertificateNo' => [
                    //     'label' => 'Aadhar Number',
                    //     'type' => 'text',
                    //     'id' => 'aadharCertificateNo'
                    // ]
                ] 
            ],

            'convPropertyPhoto' => [
                'label' => 'Latest photographs of the property showing the bona fide use',
                'id' => 'convDocPropertyPhoto',
                'multiple' => true,
                'inputs' => []
            ],
            'convAffidavit' => [
                'label' => 'Affidavit to the effect that there shall be no court case pending in any court with law',
                'id' => 'convDocAffidavit',
                'multiple' => false,
                'inputs' => [
                    'convDocAffidavitsDateOfAttestation' => [
                        'label' => 'Date of attestation',
                        'type' => 'date',
                        'id' => 'convDocAffidavitsDateOfAttestation'
                    ],
                    'convDocAffidavitAttestedBy' => [
                        'label' => 'Attested by',
                        'type' => 'text',
                        'id' => 'convDocAffidavitAttestedBy'
                    ],
                ]
            ],
        ],
        'optional' => [
            'groups' => [
                [
                    'documents' => [
                        'lesseeAliveAffidevit' => [
                            'label' => 'Affidevit to the effect that the lessee is alive',
                            'id' => 'convOptLesseeAliveAffidevit',
                            'inputs' => [
                                'convOptLesseeAliveAffidevitDocumentDate' =>  [
                                    'label' => 'Date of Document',
                                    'type' => 'date',
                                    'id' => 'convOptLesseeAliveAffidevitDocumentDate'
                                ],
                                'convOptLesseeAliveAffidevitAttestedby' =>  [
                                    'label' => 'Attested by',
                                    'type' => 'text',
                                    'id' => 'convOptLesseeAliveAffidevitAttestedby'
                                ],
                            ]
                        ],
                    ]
                ],
                [
                    'input' => [
                        'type' => 'radio',
                        'name' => 'isLeaseDeedLost',
                        'label' => 'Whether the Lease deed is lost',
                        'options' => [
                            [
                                'value' => 1,
                                'label' => 'Yes'
                            ],
                            [
                                'value' => 0,
                                'label' => 'No'
                            ]
                        ]
                    ],
                    'documents' => [
                        'leaseeDeedLostAffidevit' => [
                            'label' => 'Affidavit for Lease Deed is Lost',
                            'id' => 'convOptLeaseLostAffidevit',
                            'inputs' => [
                                'convLeaseLostAffidevitDocumentDate' =>  [
                                    'label' => 'Date of Document',
                                    'type' => 'date',
                                    'id' => 'convLeaseLostAffidevitDocumentDate'
                                ],
                                'convLeaseLostAffidevitAttestedBy' =>  [
                                    'label' => 'Attested by',
                                    'type' => 'text',
                                    'id' => 'convLeaseLostAffidevitAttestedBy'
                                ],
                            ]
                        ],
                        'leaseeDeedLostPublicNotice' => [
                            'label' => 'Public Notice in National Daily (English & Hindi)',
                            'id' => 'convOptLeaseLostPublicNotice',
                            'inputs' => [
                                'convOptLeaseLostPublicNoticeNameOfNewspaper' =>  [
                                    'label' => 'Name of Newspaper (English or Hindi)',
                                    'type' => 'text',
                                    'id' => 'convOptLeaseLostPublicNoticeNameOfNewspaper'
                                ],
                            ]
                        ]
                    ]
                ]

            ]
        ],
        'TempModelName' => 'TempConversionApplication'
    ]
];
