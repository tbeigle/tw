<?php

namespace Wooclover\CloverApi\Domain;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class Currency {

	const CUR_AED = 'AED';
	const CUR_AFN = 'AFN';
	const CUR_ALL = 'ALL';
	const CUR_AMD = 'AMD';
	const CUR_ANG = 'ANG';
	const CUR_AOA = 'AOA';
	const CUR_ARS = 'ARS';
	const CUR_AUD = 'AUD';
	const CUR_AWG = 'AWG';
	const CUR_AZN = 'AZN';
	const CUR_BAM = 'BAM';
	const CUR_BBD = 'BBD';
	const CUR_BDT = 'BDT';
	const CUR_BGN = 'BGN';
	const CUR_BHD = 'BHD';
	const CUR_BIF = 'BIF';
	const CUR_BMD = 'BMD';
	const CUR_BND = 'BND';
	const CUR_BOB = 'BOB';
	const CUR_BRL = 'BRL';
	const CUR_BSD = 'BSD';
	const CUR_BTN = 'BTN';
	const CUR_BWP = 'BWP';
	const CUR_BYR = 'BYR';
	const CUR_BZD = 'BZD';
	const CUR_CAD = 'CAD';
	const CUR_CDF = 'CDF';
	const CUR_CHE = 'CHE';
	const CUR_CHF = 'CHF';
	const CUR_CHW = 'CHW';
	const CUR_CLF = 'CLF';
	const CUR_CLP = 'CLP';
	const CUR_CNY = 'CNY';
	const CUR_COP = 'COP';
	const CUR_COU = 'COU';
	const CUR_CRC = 'CRC';
	const CUR_CUC = 'CUC';
	const CUR_CUP = 'CUP';
	const CUR_CVE = 'CVE';
	const CUR_CZK = 'CZK';
	const CUR_DJF = 'DJF';
	const CUR_DKK = 'DKK';
	const CUR_DOP = 'DOP';
	const CUR_DZD = 'DZD';
	const CUR_EGP = 'EGP';
	const CUR_ERN = 'ERN';
	const CUR_ETB = 'ETB';
	const CUR_EUR = 'EUR';
	const CUR_FJD = 'FJD';
	const CUR_FKP = 'FKP';
	const CUR_GBP = 'GBP';
	const CUR_GEL = 'GEL';
	const CUR_GHS = 'GHS';
	const CUR_GIP = 'GIP';
	const CUR_GMD = 'GMD';
	const CUR_GNF = 'GNF';
	const CUR_GTQ = 'GTQ';
	const CUR_GYD = 'GYD';
	const CUR_HKD = 'HKD';
	const CUR_HNL = 'HNL';
	const CUR_HRK = 'HRK';
	const CUR_HTG = 'HTG';
	const CUR_HUF = 'HUF';
	const CUR_IDR = 'IDR';
	const CUR_ILS = 'ILS';
	const CUR_INR = 'INR';
	const CUR_IQD = 'IQD';
	const CUR_IRR = 'IRR';
	const CUR_ISK = 'ISK';
	const CUR_JMD = 'JMD';
	const CUR_JOD = 'JOD';
	const CUR_JPY = 'JPY';
	const CUR_KES = 'KES';
	const CUR_KGS = 'KGS';
	const CUR_KHR = 'KHR';
	const CUR_KMF = 'KMF';
	const CUR_KPW = 'KPW';
	const CUR_KRW = 'KRW';
	const CUR_KWD = 'KWD';
	const CUR_KYD = 'KYD';
	const CUR_KZT = 'KZT';
	const CUR_LAK = 'LAK';
	const CUR_LBP = 'LBP';
	const CUR_LKR = 'LKR';
	const CUR_LRD = 'LRD';
	const CUR_LSL = 'LSL';
	const CUR_LTL = 'LTL';
	const CUR_LVL = 'LVL';
	const CUR_LYD = 'LYD';
	const CUR_MAD = 'MAD';
	const CUR_MDL = 'MDL';
	const CUR_MGA = 'MGA';
	const CUR_MKD = 'MKD';
	const CUR_MMK = 'MMK';
	const CUR_MNT = 'MNT';
	const CUR_MOP = 'MOP';
	const CUR_MRO = 'MRO';
	const CUR_MUR = 'MUR';
	const CUR_MVR = 'MVR';
	const CUR_MWK = 'MWK';
	const CUR_MXN = 'MXN';
	const CUR_MXV = 'MXV';
	const CUR_MYR = 'MYR';
	const CUR_MZN = 'MZN';
	const CUR_NAD = 'NAD';
	const CUR_NGN = 'NGN';
	const CUR_NIO = 'NIO';
	const CUR_NOK = 'NOK';
	const CUR_NPR = 'NPR';
	const CUR_NZD = 'NZD';
	const CUR_OMR = 'OMR';
	const CUR_PAB = 'PAB';
	const CUR_PEN = 'PEN';
	const CUR_PGK = 'PGK';
	const CUR_PHP = 'PHP';
	const CUR_PKR = 'PKR';
	const CUR_PLN = 'PLN';
	const CUR_PYG = 'PYG';
	const CUR_QAR = 'QAR';
	const CUR_RON = 'RON';
	const CUR_RSD = 'RSD';
	const CUR_RUB = 'RUB';
	const CUR_RWF = 'RWF';
	const CUR_SAR = 'SAR';
	const CUR_SBD = 'SBD';
	const CUR_SCR = 'SCR';
	const CUR_SDG = 'SDG';
	const CUR_SEK = 'SEK';
	const CUR_SGD = 'SGD';
	const CUR_SHP = 'SHP';
	const CUR_SLL = 'SLL';
	const CUR_SOS = 'SOS';
	const CUR_SRD = 'SRD';
	const CUR_SSP = 'SSP';
	const CUR_STD = 'STD';
	const CUR_SYP = 'SYP';
	const CUR_SZL = 'SZL';
	const CUR_THB = 'THB';
	const CUR_TJS = 'TJS';
	const CUR_TMT = 'TMT';
	const CUR_TND = 'TND';
	const CUR_TOP = 'TOP';
	const CUR_TRY = 'TRY';
	const CUR_TTD = 'TTD';
	const CUR_TWD = 'TWD';
	const CUR_TZS = 'TZS';
	const CUR_UAH = 'UAH';
	const CUR_UGX = 'UGX';
	const CUR_USD = 'USD';
	const CUR_UYU = 'UYU';
	const CUR_UZS = 'UZS';
	const CUR_VEF = 'VEF';
	const CUR_VND = 'VND';
	const CUR_VUV = 'VUV';
	const CUR_WST = 'WST';
	const CUR_XAF = 'XAF';
	const CUR_XAG = 'XAG';
	const CUR_XAU = 'XAU';
	const CUR_XBA = 'XBA';
	const CUR_XBB = 'XBB';
	const CUR_XBC = 'XBC';
	const CUR_XBD = 'XBD';
	const CUR_XCD = 'XCD';
	const CUR_XDR = 'XDR';
	const CUR_XFU = 'XFU';
	const CUR_XOF = 'XOF';
	const CUR_XPD = 'XPD';
	const CUR_XPF = 'XPF';
	const CUR_XPT = 'XPT';
	const CUR_YER = 'YER';
	const CUR_ZAR = 'ZAR';
	const CUR_ZMK = 'ZMK';
	const CUR_ZWL = 'ZWL';

}