<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTermsConditionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'language' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'content' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('language');
        $this->forge->createTable('terms_conditions');

        // Insert default terms for each language
        $db = \Config\Database::connect();
        $termsData = [
            [
                'language' => 'Mandarin',
                'title' => 'Syarat dan Ketentuan',
                'content' => file_get_contents(ROOTPATH . 'public/templates/terms.txt') ?: $this->getDefaultMandarinTerms(),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'language' => 'English',
                'title' => 'Terms and Conditions',
                'content' => $this->getDefaultEnglishTerms(),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'language' => 'Japanese',
                'title' => '利用規約',
                'content' => $this->getDefaultJapaneseTerms(),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'language' => 'Korean',
                'title' => '이용약관',
                'content' => $this->getDefaultKoreanTerms(),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'language' => 'German',
                'title' => 'AGB - Allgemeine Geschäftsbedingungen',
                'content' => $this->getDefaultGermanTerms(),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $db->table('terms_conditions')->insertBatch($termsData);
    }

    public function down()
    {
        $this->forge->dropTable('terms_conditions');
    }

    private function getDefaultMandarinTerms()
    {
        return <<<EOT
<!-- Terms Modal -->
<h3>1. Pendaftaran</h3>
<p>
    Pendaftaran kursus di Xihuan Mandarin Indonesia bersifat online
    melalui formulir pendaftaran yang tersedia. Calon siswa wajib
    mengisi formulir dengan data yang benar dan lengkap.
</p>
<hr />
<h3>2. Pembayaran</h3>
<ul>
    <li>
        Pembayaran biaya pendaftaran harus dilakukan setelah mengisi
        formulir pendaftaran.
    </li>
    <li>
        Pembayaran dapat dilakukan melalui transfer bank ke rekening yang
        telah ditentukan.
    </li>
    <li>
        Bukti pembayaran <strong>WAJIB</strong> dikirimkan melalui
        WhatsApp kepada admin.
    </li>
    <li>
        Admin akan memberikan Kuitansi Pembayaran setelah pembayaran
        <strong>Terkonfirmasi</strong> dan dianggap sah.
    </li>
    <li>
        Pembayaran tidak dapat dikembalikan (non-refundable) dengan alasan
        apapun.
    </li>
</ul>
<hr />
<h3>3. Ketentuan Pelaksanaan</h3>
<ul>
    <li>
        Kursus dimulai setiap tanggal 10 setiap bulan sepanjang tahun.
        Jika pada tanggal tersebut jatuh pada hari Sabtu, Minggu, atau
        Hari Libur maka akan dimajuan/disesuaikan.
    </li>
    <li>
        Khusus bagi Anda yang melakukan request untuk kursus di Luar
        Periode tanggal 10 silahkan menghubungi admin kami.
    </li>
    <li>
        Bagi siswa yg ingin melakukan garansi (mengulang kelas) tidak
        dikenakan biaya apapun kecuali biaya asrama per bulan 500k
    </li>
</ul>
<hr />
<h3>4. Ketentuan Pembatalan dan Perubahan Program</h3>
<h4>4.A. PEMBATALAN KURSUS:</h4>
<ul>
    <li>
        Konfirmasi Pembatalan Kursus wajib disampaikan maksimal 5 HARI
        sebelum pelaksanaan tanggal kursus.
    </li>
    <li>
        Konsekuensi dari pembatalan adalah biaya pendaftaran hangus.
    </li>
    <li>
        Pengembalian biaya (Refund) hanya dapat dilakukan apabila
        pembelajaran belum dimulai/dilaksanakan.
    </li>
</ul>
<hr />
<h3>5. Privasi Data</h3>
<p>
    Data pribadi siswa akan dijaga kerahasiaannya dan hanya digunakan
    untuk keperluan administrasi internal.
</p>
<hr />
<h3>6. Lain-lain</h3>
<ul>
    <li>
        Kami berhak mengubah syarat dan ketentuan sewaktu-waktu dengan
        memberitahukan terlebih dahulu.
    </li>
</ul>
EOT;
    }

    private function getDefaultEnglishTerms()
    {
        return <<<EOT
<h3>1. Registration</h3>
<p>
    Course registration at our institution is done online through the
    registration form available. Prospective students must fill out the
    form with correct and complete data.
</p>
<hr />
<h3>2. Payment</h3>
<ul>
    <li>
        Registration fee payment must be made after completing the
        registration form.
    </li>
    <li>
        Payment can be made via bank transfer to the designated account.
    </li>
    <li>
        Payment proof <strong>MUST</strong> be sent via WhatsApp to admin.
    </li>
    <li>
        Admin will provide a Payment Receipt after payment is
        <strong>Confirmed</strong> and considered valid.
    </li>
    <li>
        Payments are non-refundable for any reason.
    </li>
</ul>
<hr />
<h3>3. Implementation Terms</h3>
<ul>
    <li>
        Courses start on the 10th of every month throughout the year.
        If that date falls on Saturday, Sunday, or Holiday, it will be
        adjusted to the next business day.
    </li>
    <li>
        For requests outside the 10th period, please contact our admin.
    </li>
    <li>
        Students who want to repeat the class (warranty) are not charged
        any fee except for dormitory costs of 500k per month.
    </li>
</ul>
<hr />
<h3>4. Cancellation and Program Change Terms</h3>
<h4>4.A. COURSE CANCELLATION:</h4>
<ul>
    <li>
        Course cancellation must be submitted at least 5 DAYS before
        the course start date.
    </li>
    <li>
        Cancellation will result in forfeiture of the registration fee.
    </li>
    <li>
        Refunds can only be made if the course has not started.
    </li>
</ul>
<hr />
<h3>5. Data Privacy</h3>
<p>
    Student personal data will be kept confidential and only used for
    internal administrative purposes.
</p>
<hr />
<h3>6. Miscellaneous</h3>
<ul>
    <li>
