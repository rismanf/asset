<?php

namespace Database\Seeders;

use App\Models\AssetCategory;
use App\Models\AssetCategoryGroup;
use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //GROUP
        $AssetCategoryGroup = [
            [
                'category_group_name' => 'BUILDING',
                'category_group_code' => 'BD'
            ],
            [
                'category_group_name' => 'COMPUTER',
                'category_group_code' => 'CC'
            ],
            [
                'category_group_name' => 'FURNITURE FIXTURE',
                'category_group_code' => 'FF'
            ],
            [
                'category_group_name' => 'LAND',
                'category_group_code' => 'LD'
            ],
            [
                'category_group_name' => 'LLEASEHOLD IMPROVEMENTND',
                'category_group_code' => 'LI'
            ],
            [
                'category_group_name' => 'OFFICE EQUIPMENT',
                'category_group_code' => 'OE'
            ],
            [
                'category_group_name' => 'PULPEN',
                'category_group_code' => 'PP'
            ],
            [
                'category_group_name' => 'SOFTWARE',
                'category_group_code' => 'SW'
            ],
            [
                'category_group_name' => 'VEHICLE',
                'category_group_code' => 'VH'
            ],
        ];

        foreach ($AssetCategoryGroup as $AssetCategoryGroup) {
            AssetCategoryGroup::create($AssetCategoryGroup);
        }

        ////BRAND
        $Brand = [
            '3M',
            'ABB',
            'ABBA',
            'ACER',
            'ADVAN',
            'ADVANCE',
            'AIRLINK',
            'AKARI',
            'AMP',
            'APC',
            'APPLE',
            'APPRON',
            'ARIES',
            'ARISTON',
            'ARNET',
            'ARUBA',
            'ASCO',
            'ASTERIX',
            'ASUS',
            'ATCB ASCO',
            'ATEN',
            'AURORA',
            'AUX',
            'AVAYA',
            'AVOCENT',
            'AXIS',
            'BARCO',
            'BARRACUDA',
            'BENQ',
            'BIOLITENET',
            'BLACK BOX',
            'BLACK DECKER',
            'BLACKBERRY',
            'BLUE COAT',
            'BOSCH',
            'BOSE',
            'BROCADE',
            'BROTHER',
            'BUFFALO',
            'CANON',
            'CARRIER',
            'CASSA',
            'CERTIS',
            'CHAIRMAN',
            'CHECK POINT',
            'CHITOSE LOTUS',
            'CHKAWAI',
            'CHRISTIE',
            'CHROME',
            'CHUBBS',
            'CISCO',
            'CITEC',
            'CITRIX',
            'CLEAN AGENT',
            'CND',
            'CORSAIR',
            'CRESTRON',
            'CRIMSON',
            'CUMMINS',
            'CUSTOM',
            'CYBEROAM',
            'DAHLE',
            'DAHUA',
            'DAICHIBAN',
            'DAIHATSU',
            'DAIKIN',
            'DATALITE',
            'DATALOGIC',
            'DATASCRIPT',
            'DATWYLER',
            'DELL',
            'DELTA',
            'DENPOO',
            'DIGITOOL',
            'DINASTY CND',
            'D-LINK',
            'DROBO',
            'EATON',
            'EBARA',
            'ELECTROLUX',
            'EMERSON',
            'EMERSON LIEBERT',
            'ENGENIUS',
            'EPPOS',
            'EPSON',
            'EXTRON',
            'F5 NETWORKS',
            'FENWAL',
            'FIAMM',
            'FINEST',
            'FIRESAFE',
            'FLUKE',
            'FOCUSVISION',
            'FORTIGATE',
            'FUJI XEROX',
            'FUJITSU',
            'GUNNEBO',
            'HARMAN KARDON',
            'HAYWARD',
            'HID',
            'HIGH POINT',
            'HIKVISION',
            'HITACHI',
            'HONDA',
            'HP',
            'HUAWEI',
            'HYUNDAI',
            'IBM',
            'INDORACK',
            'INFOCUS',
            'JABRA',
            'JBL',
            'JUNIPER',
            'KENWOOD',
            'KIDDE',
            'KIRIN',
            'KOHLER',
            'KONE',
            'KRISBOW',
            'KYORITSU',
            'LAPLACE',
            'LENOVO',
            'LG',
            'LIEBERT',
            'LION',
            'LITEVISION',
            'LOGITECH',
            'MAKITA',
            'MAN',
            'MARELLI',
            'MARSHALL',
            'MASPION',
            'MICROSOFT',
            'MIDEA',
            'MIFARE',
            'MIKROTIK',
            'MITSUBISHI',
            'MIYAKO',
            'MODERA',
            'MOTOROLA',
            'MSI',
            'NAKAI',
            'NIKON',
            'NOKIA',
            'OSSO',
            'OTHER',
            'OZEKI',
            'PALO ALTO',
            'PANASONIC',
            'PELCO',
            'PENGUIN',
            'PERKINS',
            'PHILLIPS',
            'PICOBOX',
            'PIONEER',
            'POLYCOM',
            'POLYTRON',
            'QNAP',
            'RAKITAN',
            'REALME',
            'RICOH',
            'RIELLO AROS',
            'RIVERBED',
            'SAFEASD',
            'SAMSUNG',
            'SANGFOR',
            'SANKEN',
            'SANWA',
            'SANYO',
            'SCHNEIDER',
            'SEIKO',
            'SERVVO',
            'SHARP',
            'SHURE',
            'SICOMA',
            'SIEMMENS',
            'SIGMATIC',
            'SIMETRI',
            'SINYOKU',
            'SOCOMEC',
            'SOLUNA',
            'SONY',
            'SOURCEFIRE',
            'STRAMM',
            'STULZ',
            'SUNSTONE',
            'SYMBOL',
            'TOSHIBA',
            'TOTO',
            'TP LINK',
            'TRAFINDO',
            'UBIQUITI',
            'UNIFLAIR',
            'VEKTOR',
            'VIVOTEK',
            'WATCHGUARD',
            'WEIRWEI',
            'WESTERN DIGITAL',
            'XIAOMI',
            'XTRAILS',
            'YAMAHA',
            'YORITSU',
            'YUASA',
            'ZTE',
            'ZYREX',

        ];

        foreach ($Brand as $Brand) {
            Brand::create(['brand_name' => $Brand]);
        }
    }
}
