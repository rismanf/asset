<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer_name = [
            'PT. TELKOM INDONESIA (PERSERO) TBK',
            'PT. DELAPAN ANUGRAH SAHABAT',
            'PT. KIMIA FARMA TBK',
            'PT. TELTRANET APLIKASI SOLUSI (TELKOM TELSTRA)',
            'PT. APLIKANUSA LINTASARTA',
            'PT. PELAYARAN NASIONAL INDONESIA (PERSERO)',
            'KEMENTRIAN KELAUTAN DAN PERIKANAN REPUBLIK INDONESIA',
            'PT. BHANDA GHARA REKSA (PERSERO)',
            'BADAN METEOROLOGI, KLIMATOLOGI, DAN GEOFISIKA',
            'KEMENTERIAN RISET TEKNOLOGI DAN PENDIDIKAN TINGGI REPUBLIK INDONESIA',
            'PERUSAHAAN UMUM PERCETAKAN UANG REPUBLIK INDONESIA',
            'RADIO REPUBLIK INDONESIA',
            'PT. GARUDAFOOD PUTRA PUTRI JAYA TBK',
            'PUSDATA KEMENTERIAN KOMUNIKASI DAN INFORMATIKA',
            'DINAS KOMUNIKASI INFORMARTIKA',
            'PT. MCP INDO UTAMA',
            'PT. IFORTE SOLUSI INFOTEK',
            'PT. LG CNS INDONESIA',
            'PETRONAS CARIGALI MURIAH LTD INDONESIA',
            'PT. MEDIA AKSES GLOBAL INDO',
            'KEMENTERIAN KESEHATAN REPUBLIK INDONESIA',
            'PT. ANEKA TAMBANG (PERSERO)',
            'PT. SUPRA PRIMATAMA NUSANTARA (BIZNET)',
            'PT. SEMEN INDONESIA (PERSERO) TBK',
            'PT. ASURANSI JIWASRAYA (PERSERO)',
            'BADAN PENGAWASAN KEUANGAN DAN PEMBANGUNAN (BPKP)',
            'PT. JALIN PEMBAYARAN NUSANTARA',
            'PT. BIRO KLASIFIKASI INDONESIA (PERSERO)',
            'PT. INDONESIA COMNETS PLUS',
            'PT. BANK KB BUKOPIN',
            'PT. KRAKATAU STEEL TBK',
            'PT. ARTHATECH SELARAS',
            'PT. PUPUK INDONESIA (PERSERO)',
            'PT. ASURANSI SOSIAL ANGKATAN BERSENJATA REPUBLIK INDONESIA (PERSERO)',
            'PT. ASURANSI JASA INDONESIA (PERSERO)',
            'PT. DJELAS TANDATANGAN BERSAMA',
            'PT. INDOSAT TBK',
            'PT. WORLEYPARSONS INDONESIA',
            'PT. CENTRAL PROTEINA PRIMA (CPP)',
            'PT. FINNET INDONESIA',
            'PT. PASIFIK SATELIT NUSANTARA',
            'PT. ASURANSI JIWA INDOSURYA SUKSES',
            'PT. PRIMACOM INTERBUANA',
            'PT. JALA LINTAS MEDIA',
            'CLOUD4C SERVICES PTE.LTD',
            'PT. NEW PRIOK CONTAINER TERMINAL ONE',
            'PT. KERETA COMMUTER INDONESIA',
            'PEMERINTAHAN PROVINSI DKI JAKARTA',
            'PT. PELABUHAN INDONESIA I (PERSERO)',
            'BADAN KOORDINASI PENANAMAN MODAL',
            'PT. SIGMA CIPTA CARAKA',
            'PT. COCA COLA AMATIL INDONESIA',
            'PT. INDONUSA SYSTEM INTEGRATOR PRIMA',
            'PT. GLOBAL DIGITAL NIAGA',
            'PT. ASURANSI JIWA BERSAMA BUMIPUTRA',
            'PT. JAMINAN PEMBIAYAAN ASKRINDO SYARIAH',
            'PT. INFOKOM ELEKTRINDO',
            'PT. PERTAMINA (PERSERO)',
            'PT. MASTERCARD INDONESIA',
            'PT. XL AXIATA TBK',
            'PT. LINK NET TBK',
            'PT. MEGA AKSES PERSADA (FIBERSTAR)',
            'PT. IBM INDONESIA',
            'PT. CHAROEN POKPHAND INDONESIA TBK',
            'PT. BANK TABUNGAN PENSIUNAN NASIONAL SYARIAH',
            'PT. DIGITAL TANDATANGAN ASLI',
            'PT. ASURANSI KREDIT INDONESIA (PERSERO)',
            'PT. BANK NEO COMMERCE TBK',
            'PT. JAYA TEKNOLOGI INTERNASIONAL (JTI)',
            'PT. BANK DAERAH KHUSUS IBUKOTA',
            'DEUTSCHE BANK INDONESIA',
            'PT. BANK TABUNGAN PENSIUNAN NASIONAL TBK',
            'PT. TELEKOMUNIKASI INDONESIA TBK-ALWAYS ON',
            'PT. JAPFA COMFEED INDONESIA TBK',
            'PT. BANK RAKYAT INDONESIA TBK',
            'PT. NAP INFO LINTAS NUSA',
            'PT. BANK TABUNGAN NEGARA TBK',
            'PT. BANK IBK INDONESIA TBK (AGRS)',
            'PT. FIBER NETWORKS INDONESIA',
            'PT. PEGADAIAN (PERSERO)',
            'PT. PRIVY IDENTITAS DIGITAL',
            'PT. TIPHONE MOBILE INDONESIA',
            'PT. ASURANSI AIA INDONESIA',
            'PT. COLLEGA INTI PRATAMA',
            'OTORITAS JASA KEUANGAN REPUBLIK INDONESIA',
            'PT. RINTIS SEJAHTERA',
            'BOS TOKO',
            'KEMENTERIAN PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK REPUBLIK INDONESIA',
            'PT. PELABUHAN INDONESIA II  (PERSERO)',
            'PT. INTEGRASI LOGISTIK CIPTA SOLUSI',
            'PT. INDOINTERNET TBK',
            'PT. CYBERINDO ADITAMA (CBN)',
            'PT. MORA TELEMATIKA INDONESIA',
            'PT. MITRA TRANSAKSI INDONESIA',
            'BADAN NASIONAL PENANGGULANGAN BENCANA',
            'KEJAKSAAN AGUNG REPUBLIK INDONESIA',
            'KEMENTERIAN PERDAGANGAN - BADAN PENGAWAS PERDAGANGAN BERJANGKA KOMODITI',
            'PT. TRESSA LESTARI',
            'BADAN PENGELOLA KEUANGAN HAJI',
            'KEMENTERIAN AGAMA REPUBLIK INDONESIA',
            'PT. TELEKOMUNIKASI INDONESIA INTERNATIONAL SINGAPORE - ZENLAYER',
            'PT. POWER TELECOM',
            'PT. CLIPAN FINANCE INDONESIA TBK',
            'PT. FIBER MEDIA NUSANTARA',
            'PT. BANK MAYBANK INDONESIA TBK',
            'HUAWEI TECH INVESTMENT CO LTD',
            'PT. PAKAI DONK NUSANTARA',
            'PT. PRAWATHIYA KARSA PRADIPTHA',
            'PT. PEMBANGUNAN PERUMAHAN (PERSERO) TBK',
            'PT. BURSA EFEK INDONESIA',
            'PT. TRANSINDO NETWORK',
            'PT. WAHANA PEMBAYARAN DIGITAL',
            'PT. INDOFARMA TBK',
            'PT. ASURANSI ALLIANZ UTAMA INDONESIA',
            'PT. BANK OF INDIA INDONESIA TBK',
            'PT. TONGDUN TECHNOLOGY INDONESIA',
            'PT. BANK MASPION',
            'PT. BANK HSBC INDONESIA',
            'PT. BANK VICTORIA INTERNATIONAL TBK',
            'PT. BANK PEMBANGUNAN DAERAH KALIMANTAN BARAT',
            'PT. BANK PEMBANGUNAN DAERAH KALIMANTAN BARAT SYARIAH',
            'PT. ASDP INDONESIA FERRY',
            'PT. ARTHA TELEKOMINDO (ARTHATEL)',
            'PT. DBS VICKERS SEKURITAS INDONESIA',
            'PT. BANK COMMONWEALTH',
            'PT. XAPIENS TEKNOLOGI INDONESIA',
            'DINAS KOMUNIKASI INFORMARTIKA DAN STATISTIK MAGELANG',
            'SEKRETARIAT JENDERAL DPR RI',
            'PT. SOFTEX INDONESIA',
            'CHINA TELECOM CORP LTD',
            'PT. PEFINDO BIRO KREDIT',
            'KOMISI PEMBERANTASAN KORUPSI REPUBLIK INDONESIA',
            'BADAN PENGELOLA PENDAPATAN DAERAH PROVINSI JAWA TENGAH',
            'PDAM SURYA SEMBADA KOTA SURABAYA',
            'PERUSAHAAN UMUM PERHUTANI',
            'PT. TELEKOMUNIKASI INDONESIA TBK',
            'PT. SARANA YUKTI BANDHANA',
            'PT. ALADIN BANK',
            'PT. NTT INDONESIA',
            'PT. INDOMOBIL FINANCE INDONESIA',
            'PT. REPSOL INDONESIA',
            'AMAZON WEB SERVICES',
            'PT. BANK PEMBANGUNAN DAERAH BANTEN',
            'PT. PAPA MAMA BAHAGIA',
            'PT. BANK PEMBANGUNAN DAERAH KALIMANTAN TIMUR DAN KALIMANTAN UTARA SYARIAH',
            'PT. BANK PANIN INDONESIA TBK',
            'SINGAPORE TELECOMMUNICATIONS INDONESIA',
            'PT. BANK JTRUST INDONESIA TBK',
            'PT. BANK CTBC INDONESIA',
            'PT. BANK PEMBANGUNAN DAERAH JAWA TIMUR',
            'BP BERAU LTD',
            'PT. BANK DBS INDONESIA',
            'PT. BANK MALUKU',
            'PT. NTT INDONESIA SOLUTIONS',
            'PT. SOMPO INSURANCE INDONESIA',
            'PT. PERTAMINA HULU MAHAKAM',
            'PT. KUSTODIAN SENTRAL EFEK INDONESIA',
            'BLOOMBERG L.P',
            'PT. ADMINISTRASI MEDIKA',
            'PT. ASTRA CREDIT COMPANY (ACC)',
            'PT. ENKRIPA TEKNOLOGI INDONESIA',
            'PT. BANK VICTORIA SYARIAH TBK',
            'PT. FWD INSURANCE INDONESIA',
            'PT. BANK SBI INDONESIA',
            'BANK OF TOKYO - MITSUBISHI UFJ',
            'PT. KLIRING PENJAMINAN EFEK INDONESIA',
            'PT. CIMB NIAGA AUTO FINANCE (CNAF)',
            'PT. BANK ANZ INDONESIA',
            'PT. FEDERAL INTERNATIONAL FINANCE',
            'PT. BANK WOORI SAUDARA INDONESIA 1906 TBK',
            'AT&T',
            'BADAN AKSESIBILITAS TELEKOMUNIKASI DAN INFORMASI (BAKTI) KEMENTERIAN KOMUNIKASI DAN INFORMATIKA',
            'UNIVERSITAS GADJAH MADA',
            'PT. HADJI KALLA',
            'BADAN PENYELENGGARA JAMINAN SOSIAL KESEHATAN',
            'PT. ALAM JAYA PRIMANUSA',
            'PT. BUSSAN AUTO FINANCE',
            'KANTOR STAFF PRESIDEN REPUBLIK INDONESIA',
            'PERUM JASA TIRTA (PJT) I',
            'SEKRETARIAT NEGARA REPUBLIK INDONESIA',
            'PT. INDUSTRI KERETA API (PERSERO)',
            'LAYANAN PENGADAAN SECARA ELEKTRONIK PEMERINTAH KABUPATEN SIDOARJO',
            'PT. DUTA TEKNOLOGI KREATIF',
            'PT. TILAKA NUSA TEKNOLOGI',
            'PT. SAMPOERNA TELEKOMUNIKASI INDONESIA',
            'PT. BANK PANIN DUBAI SYARIAH TBK',
            'PT. MANDIRI UTAMA FINANCE',
            'PT. JASA RAHARJA',
            'BANK SYARIAH INDONESIA',
            'PT. BPR BANK SLEMAN (PERSERODA)',
            'PT. ADIRA DINAMIKA MULTI FINANCE',
        ];


        foreach ($customer_name as $customer_name) {
            Customer::create(['customer_name' => $customer_name]);
        }
    }
}
