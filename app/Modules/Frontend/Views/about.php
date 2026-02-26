<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('content') ?>
<!-- About Header -->
<div class="hero-section py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Tentang Kami</h1>
                <p class="lead mb-0">SOS Course & Training - Lembaga Pendidikan Bahasa Asing di Kampung Inggris, Pare, Kediri</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <div class="badge bg-white text-dark p-2 px-3 rounded-pill shadow-sm">
                    <i class="bi bi-award-fill me-2 text-primary"></i>Beroperasi Sejak 2013
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 mb-4">
            <div class="sticky-top" style="top: 2rem;">
                <div class="card-custom border-0 shadow-sm overflow-hidden">
                    <div class="list-group list-group-flush" id="about-nav">
                        <a href="#tentang" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="bi bi-info-circle me-2"></i> Tentang Kami
                        </a>
                        <a href="#sejarah" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="bi bi-clock-history me-2"></i> Sejarah
                        </a>
                        <a href="#visimisi" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="bi bi-bullseye me-2"></i> Visi & Misi
                        </a>
                        <a href="#program" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="bi bi-book me-2"></i> Program Kursus
                        </a>
                        <a href="#inggris" class="list-group-item list-group-item-action border-0 py-3 ps-5">
                            <i class="bi bi-translate me-2"></i> Bahasa Inggris
                        </a>
                        <a href="#mandarin" class="list-group-item list-group-item-action border-0 py-3 ps-5">
                            <i class="bi bi-translate me-2"></i> Bahasa Mandarin
                        </a>
                        <a href="#jepang" class="list-group-item list-group-item-action border-0 py-3 ps-5">
                            <i class="bi bi-translate me-2"></i> Bahasa Jepang
                        </a>
                        <a href="#korea" class="list-group-item list-group-item-action border-0 py-3 ps-5">
                            <i class="bi bi-translate me-2"></i> Bahasa Korea
                        </a>
                        <a href="#jerman" class="list-group-item list-group-item-action border-0 py-3 ps-5">
                            <i class="bi bi-translate me-2"></i> Bahasa Jerman
                        </a>
                        <a href="#lingkungan" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="bi bi-geo-alt me-2"></i> Kampung Inggris
                        </a>
                        <a href="#keunggulan" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="bi bi-star me-2"></i> Keunggulan
                        </a>
                    </div>
                </div>
                
                <div class="card-custom bg-light border-0 mt-4 p-4">
                    <h6 class="fw-bold mb-2">Hubungi Kami</h6>
                    <p class="small text-muted mb-0">Kampus: Kampung Inggris, Pare, Kediri, Jawa Timur</p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- About Section -->
            <section id="tentang" class="mb-5">
                <h2 class="fw-bold mb-4 d-flex align-items-center">
                    <span class="feature-icon ms-0 me-3 shadow-sm" style="width: 40px; height: 40px; font-size: 1rem;">
                        <i class="bi bi-info-circle"></i>
                    </span>
                    Tentang SOS Course & Training
                </h2>
                <div class="card-custom p-4 border-0 shadow-sm">
                    <p class="lead">SOS Course & Training adalah lembaga pendidikan non-formal yang berfokus pada pembelajaran 5 bahasa asing di Kampung Inggris, Pare, Kediri.</p>
                    <p>Sejak berdiri pertama kali di tahun 2013, kami berkomitmen menyediakan program kursus yang berkualitas dan relevan dengan kebutuhan era global. Saat ini, SOS Course & Training menawarkan lima program bahasa asing, yaitu:</p>
                    
                    <div class="row mt-4 g-3">
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded text-center">
                                <h3 class="fw-bold text-primary mb-1">5</h3>
                                <div class="small fw-semibold">Bahasa Asing</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded text-center">
                                <h3 class="fw-bold text-success mb-1">2013</h3>
                                <div class="small fw-semibold">Tahun Berdiri</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded text-center">
                                <h3 class="fw-bold text-danger mb-1">3000+</h3>
                                <div class="small fw-semibold">Alumni</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-primary bg-opacity-10 rounded border-start border-primary border-4">
                        <p class="mb-0">Sebagai pusat pembelajaran, kami menyadari bahwa penguasaan bahasa asing tidak hanya sebatas keterampilan komunikasi, melainkan juga sarana untuk memahami budaya, memperluas wawasan, serta membuka peluang akademik dan profesional. Karena itu, setiap program kami dirancang secara sistematis dengan menggabungkan pendekatan komunikatif, praktik intensif, serta pemahaman budaya dari bahasa yang dipelajari.</p>
                    </div>
                </div>
            </section>

            <!-- History Section -->
            <section id="sejarah" class="mb-5">
                <h2 class="fw-bold mb-4 d-flex align-items-center">
                    <span class="feature-icon ms-0 me-3 shadow-sm" style="width: 40px; height: 40px; font-size: 1rem;">
                        <i class="bi bi-clock-history"></i>
                    </span>
                    Jejak Langkah SOS Course & Training
                </h2>
                
                <div class="row g-4">
                    <!-- 2013 - Beginning -->
                    <div class="col-12">
                        <div class="card-custom border-0 shadow-sm h-100 overflow-hidden">
                            <div class="bg-warning p-2"></div>
                            <div class="p-4">
                                <h4 class="fw-bold"><i class="bi bi-sun me-2 text-warning"></i>1. Fajar Pertama di Kampung Inggris (2013)</h4>
                                <p class="text-muted">Kisah SOS Course & Training bermula pada tahun 2013 di pusat pendidikan bahasa terbesar di Indonesia, Kampung Inggris Pare.</p>
                                <p>Sejak awal pendiriannya, SOS telah mengusung visi yang berani dengan menghadirkan Program 5 Bahasa sekaligus. Hal ini menjadikannya salah satu pionir lembaga multibahasa di kawasan tersebut, memberikan pilihan yang lebih luas bagi para pencari ilmu.</p>
                                <div class="alert alert-info border-0 shadow-sm mt-3 mb-0">
                                    <i class="bi bi-geo-alt me-2"></i><strong>Fase Nomaden:</strong> Pada masa awal, SOS menjalani perjalanan operasional yang berpindah-pindah di jalan-jalan ikonik Tulungrejo - mulai dari Jl. Asparaga, beralih ke Jl. Anyelir, hingga sempat menetap di Jl. Sakura.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pandemic Period -->
                    <div class="col-12">
                        <div class="card-custom border-0 shadow-sm h-100 overflow-hidden">
                            <div class="bg-secondary p-2"></div>
                            <div class="p-4">
                                <h4 class="fw-bold"><i class="bi bi-shield-exclamation me-2 text-secondary"></i>2. Ujian Pandemi: Bertahan di Tengah Kelumpuhan Kota (2020-2022)</h4>
                                <p class="text-muted">Tahun 2020 menjadi titik balik yang paling menguji daya tahan.</p>
                                <p>Pandemi COVID-19 menghantam dunia, dan Kampung Inggris Pare yang biasanya berdenyut dengan ribuan pelajar seketika lumpuh total. Kebijakan pembatasan sosial memaksa banyak lembaga kursus gulung tikar.</p>
                                <div class="alert alert-success border-0 shadow-sm mt-3 mb-0">
                                    <i class="bi bi-arrow-up-circle me-2"></i><strong>Kesuksesan:</strong> SOS tetap bertahan dengan merombak metode pengajaran dan mempersiapkan infrastruktur yang lebih kuat. Pasca-pandemi, SOS muncul dengan energi yang jauh lebih besar dan standar kualitas yang lebih matang.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Career Partnerships -->
                    <div class="col-12">
                        <div class="card-custom border-0 shadow-sm h-100 overflow-hidden">
                            <div class="bg-primary p-2"></div>
                            <div class="p-4">
                                <h4 class="fw-bold"><i class="bi bi-briefcase me-2 text-primary"></i>3. Jembatan Karier dan Kemitraan Strategis</h4>
                                <p>SOS Course & Training berhasil mengubah citra kursus bahasa menjadi gerbang karier nyata. Melalui kerja sama yang erat dengan berbagai instansi, SOS berperan aktif dalam:</p>
                                <ul>
                                    <li><strong>Penyelenggaraan Pelatihan:</strong> Mengadakan in-house training bahasa bagi instansi pemerintah maupun swasta.</li>
                                    <li><strong>Program OJT ke Cina:</strong> Mengirimkan kandidat terbaik untuk merasakan pengalaman kerja langsung di Tiongkok.</li>
                                    <li><strong>Penyaluran Tenaga Kerja:</strong> Menjadi mitra strategis dalam proses interview dengan HRD perusahaan-perusahaan besar berbasis Cina di Indonesia.</li>
                                </ul>
                                <div class="mt-3 p-3 bg-light rounded">
                                    <strong class="text-primary">Mitra Perusahaan:</strong> IMIP (Morowali), IWIP (Weda Bay), OSS (Konawe), CCECP, Global Textile, dan berbagai manufaktur besar lainnya.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modern Facilities -->
                    <div class="col-12">
                        <div class="card-custom border-0 shadow-sm h-100 overflow-hidden">
                            <div class="bg-success p-2"></div>
                            <div class="p-4">
                                <h4 class="fw-bold"><i class="bi bi-building me-2 text-success"></i>4. Stabilitas dan Fasilitas Modern</h4>
                                <p>Kegigihan selama bertahun-tahun membuahkan hasil manis dengan berdirinya fasilitas permanen yang representatif.</p>
                                
                                <div class="row mt-3 g-3">
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded h-100">
                                            <h6 class="fw-bold"><i class="bi bi-house me-2"></i>Kantor Pusat & Fasilitas Utama</h6>
                                            <p class="mb-0 small">Perumahan Green Pare Residence 1, Jl. PB. Sudirman, Tulungrejo, Pare, Kediri.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded h-100">
                                            <h6 class="fw-bold"><i class="bi bi-star me-2"></i>Fasilitas Terbaru (Kirana Cluster)</h6>
                                            <p class="mb-0 small">Terletak di Perumahan Kirana Cluster, Tulungrejo, Pare, Kediri. Diririkan untuk mengakomodasi antusiasme siswa yang terus meningkat.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Future Commitment -->
                    <div class="col-12">
                        <div class="card-custom border-0 shadow-sm h-100 overflow-hidden">
                            <div class="bg-danger p-2"></div>
                            <div class="p-4">
                                <h4 class="fw-bold"><i class="bi bi-flag me-2 text-danger"></i>5. Komitmen Masa Depan</h4>
                                <p class="mb-0">Memasuki dekade kedua perjalanannya, SOS Course & Training tetap memegang teguh komitmennya untuk terus meningkatkan kualitas pendidikan, pembaruan fasilitas penunjang yang modern, serta perluasan networking baik di dalam maupun luar negeri.</p>
                                <div class="alert alert-warning border-0 shadow-sm mt-3 mb-0">
                                    <i class="bi bi-lightbulb me-2"></i><strong>SOS bukan sekadar tempat belajar bahasa, melainkan ekosistem pertumbuhan yang menghubungkan potensi lokal dengan peluang karir global.</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Vision & Mission Section -->
            <section id="visimisi" class="mb-5">
                <h2 class="fw-bold mb-4 d-flex align-items-center">
                    <span class="feature-icon ms-0 me-3 shadow-sm" style="width: 40px; height: 40px; font-size: 1rem;">
                        <i class="bi bi-bullseye"></i>
                    </span>
                    Visi dan Misi
                </h2>
                
                <div class="row g-4">
                    <div class="col-12">
                        <div class="card-custom border-0 shadow-sm h-100 overflow-hidden">
                            <div class="bg-success p-2"></div>
                            <div class="p-4">
                                <h4 class="fw-bold"><i class="bi bi-eye me-2 text-success"></i>Visi Kami</h4>
                                <p class="mb-0">Menjadi lembaga kursus bahasa asing unggulan yang mampu mencetak generasi pembelajar berdaya saing global melalui penguasaan bahasa dan pemahaman lintas budaya.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="card-custom border-0 shadow-sm h-100 overflow-hidden">
                            <div class="bg-primary p-2"></div>
                            <div class="p-4">
                                <h4 class="fw-bold"><i class="bi bi-list-task me-2 text-primary"></i>Misi Kami</h4>
                                <ul class="mb-0">
                                    <li>Menyelenggarakan program pembelajaran bahasa asing yang terstruktur, praktis, dan efektif.</li>
                                    <li>Menyediakan tenaga pengajar yang kompeten dan berpengalaman, termasuk tutor yang memiliki latar belakang akademik dan budaya dari negara terkait.</li>
                                    <li>Membentuk lingkungan belajar yang mendukung, disiplin, dan kondusif bagi siswa untuk berlatih menggunakan bahasa secara aktif.</li>
                                    <li>Membantu siswa mencapai tujuan spesifik, baik untuk studi, karier, maupun pengembangan diri.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Programs Section -->
            <section id="program" class="mb-5">
                <h2 class="fw-bold mb-4 d-flex align-items-center">
                    <span class="feature-icon ms-0 me-3 shadow-sm" style="width: 40px; height: 40px; font-size: 1rem;">
                        <i class="bi bi-book"></i>
                    </span>
                    Program Kursus dan Pelatihan
                </h2>
                
                <div class="alert alert-info border-0 shadow-sm mb-4">
                    <i class="bi bi-info-circle me-2"></i>Kami membuka Program Kursus dan Pelatihan 5 Bahasa Asing terpopuler di Dunia khususnya di Indonesia karena dapat membuka peluang besar bagi mereka yang menguasai bahasa-bahasa tersebut.
                </div>

                <div class="row g-4">
                    <!-- Bahasa Inggris -->
                    <div id="inggris" class="col-12 scroll-mt" style="scroll-margin-top: 2rem;">
                        <div class="card-custom border-0 shadow-sm h-100 overflow-hidden">
                            <div class="bg-warning p-2"></div>
                            <div class="p-4">
                                <h4 class="fw-bold"><i class="bi bi-translate me-2 text-warning"></i>Bahasa Inggris</h4>
                                <p class="text-muted">Bahasa internasional yang menjadi syarat utama untuk melanjutkan studi ke berbagai negara.</p>
                                <ul class="mb-0">
                                    <li>Syarat utama studi ke AS, Inggris, Australia, dan negara Eropa lainnya.</li>
                                    <li>Kualifikasi penting dalam dunia kerja global.</li>
                                    <li>Program: Reguler, Semi-Privat, Privat (Online/Offline)</li>
                                    <li>Fasilitas: Camp/Asrama English Area, modul, sertifikat, kaos eksklusif</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Bahasa Mandarin -->
                    <div id="mandarin" class="col-12 scroll-mt" style="scroll-margin-top: 2rem;">
                        <div class="card-custom border-0 shadow-sm h-100 overflow-hidden">
                            <div class="bg-danger p-2"></div>
                            <div class="p-4">
                                <h4 class="fw-bold"><i class="bi bi-translate me-2 text-danger"></i>Bahasa Mandarin</h4>
                                <p class="text-muted">Bahasa dengan jumlah penutur terbesar di dunia.</p>
                                <ul class="mb-0">
                                    <li>Peluang besar studi di Tiongkok/Taiwan melalui beasiswa internasional.</li>
                                    <li>Akses karier global di sektor perdagangan, teknologi, pariwisata.</li>
                                    <li>Fokus pada keterampilan mendengar, berbicara, membaca Hanzi, dan menulis.</li>
                                    <li>Persiapan HSK (Hanyu Shuiping Kaoshi)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Bahasa Jepang -->
                    <div id="jepang" class="col-12 scroll-mt" style="scroll-margin-top: 2rem;">
                        <div class="card-custom border-0 shadow-sm h-100 overflow-hidden">
                            <div class="bg-pink p-2"></div>
                            <div class="p-4">
                                <h4 class="fw-bold"><i class="bi bi-translate me-2" style="color: #ff6b9d;"></i>Bahasa Jepang</h4>
                                <p class="text-muted">Bahasa dengan peluang studi dan kerja di Jepang.</p>
                                <ul class="mb-0">
                                    <li>Peluang studi melalui program beasiswa MEXT dan universitas.</li>
                                    <li>Kesempatan kerja di industri, kesehatan, teknologi.</li>
                                    <li>Fokus: Mendengar, berbicara, membaca, menulis (Hiragana, Katakana, Kanji)</li>
                                    <li>Persiapan JLPT (Japanese Language Proficiency Test)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Bahasa Korea -->
                    <div id="korea" class="col-12 scroll-mt" style="scroll-margin-top: 2rem;">
                        <div class="card-custom border-0 shadow-sm h-100 overflow-hidden">
                            <div class="bg-info p-2"></div>
                            <div class="p-4">
                                <h4 class="fw-bold"><i class="bi bi-translate me-2 text-info"></i>Bahasa Korea</h4>
                                <p class="text-muted">Bahasa asing yang sangat populer karena budaya Korea.</p>
                                <ul class="mb-0">
                                    <li>Pengaruh K-Pop, K-Drama, K-Movie</li>
                                    <li>Kesempatan studi di Korea Selatan melalui program beasiswa.</li>
                                    <li>Peluang karier di teknologi, manufaktur, kesehatan.</li>
                                    <li>Fokus: Percakapan praktis, tata bahasa, Hangul, budaya Korea</li>
                                    <li>Persiapan TOPIK</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Bahasa Jerman -->
                    <div id="jerman" class="col-12 scroll-mt" style="scroll-margin-top: 2rem;">
                        <div class="card-custom border-0 shadow-sm h-100 overflow-hidden">
                            <div class="bg-secondary p-2"></div>
                            <div class="p-4">
                                <h4 class="fw-bold"><i class="bi bi-translate me-2 text-secondary"></i>Bahasa Jerman</h4>
                                <p class="text-muted">Bahasa penting di Eropa dengan akses luas pendidikan tinggi.</p>
                                <ul class="mb-0">
                                    <li>Akses melanjutkan studi di Jerman dengan kualitas tinggi.</li>
                                    <li>Banyak pilihan beasiswa dan biaya kuliah terjangkau.</li>
                                    <li>Karier di teknik, kesehatan, penelitian.</li>
                                    <li>Persiapan Goethe-Zertifikat</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Learning Environment Section -->
            <section id="lingkungan" class="mb-5">
                <h2 class="fw-bold mb-4 d-flex align-items-center">
                    <span class="feature-icon ms-0 me-3 shadow-sm" style="width: 40px; height: 40px; font-size: 1rem;">
                        <i class="bi bi-geo-alt"></i>
                    </span>
                    Suasana Belajar di Kampung Inggris
                </h2>
                
                <div class="card-custom border-0 shadow-sm p-4">
                    <p>SOS Course & Training berlokasi di Kampung Inggris, Pare, Kediri, sebuah pusat pembelajaran bahasa asing yang telah berkembang sejak 1977. Lingkungan ini dikenal dengan ekosistem multibahasa yang unik, didukung ratusan lembaga kursus dan aturan English Area yang mendorong praktik bahasa setiap hari.</p>
                    
                    <div class="row mt-4 g-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="fw-bold text-success"><i class="bi bi-check-circle me-2"></i>Keunggulan Lingkungan</h6>
                                <ul class="mb-0 small">
                                    <li>Suasana inklusif dan suportif</li>
                                    <li>Interaksi dengan pelajar dari seluruh Nusantara</li>
                                    <li>Fasilitas: asrama, café, taman</li>
                                    <li>Nuansa pedesaan yang asri</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="fw-bold text-warning"><i class="bi bi-exclamation-triangle me-2"></i>Catatan</h6>
                                <p class="mb-0 small">Tantangan tetap ada—dari fasilitas sederhana hingga lokasi desa. Namun, faktor terpenting tetap pada kualitas pengajaran dan metode, bukan megahnya infrastruktur.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Advantages Section -->
            <section id="keunggulan" class="mb-5">
                <h2 class="fw-bold mb-4 d-flex align-items-center">
                    <span class="feature-icon ms-0 me-3 shadow-sm" style="width: 40px; height: 40px; font-size: 1rem;">
                        <i class="bi bi-star"></i>
                    </span>
                    Keunggulan SOS Course & Training
                </h2>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card-custom border-0 shadow-sm h-100 p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="feature-icon me-3" style="width: 50px; height: 50px;">
                                    <i class="bi bi-translate"></i>
                                </div>
                                <h5 class="fw-bold mb-0">Program Lengkap</h5>
                            </div>
                            <p class="text-muted mb-0">Lima bahasa asing dalam satu lembaga.</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card-custom border-0 shadow-sm h-100 p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="feature-icon me-3" style="width: 50px; height: 50px;">
                                    <i class="bi bi-lightbulb"></i>
                                </div>
                                <h5 class="fw-bold mb-0">Metode Interaktif</h5>
                            </div>
                            <p class="text-muted mb-0">Pembelajaran interaktif dan aplikatif, menekankan praktik langsung.</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card-custom border-0 shadow-sm h-100 p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="feature-icon me-3" style="width: 50px; height: 50px;">
                                    <i class="bi bi-people"></i>
                                </div>
                                <h5 class="fw-bold mb-0">Tutor Berpengalaman</h5>
                            </div>
                            <p class="text-muted mb-0">Tenaga pengajar berpengalaman dengan pendekatan sesuai kebutuhan siswa.</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card-custom border-0 shadow-sm h-100 p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="feature-icon me-3" style="width: 50px; height: 50px;">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <h5 class="fw-bold mb-0">Lokasi Strategis</h5>
                            </div>
                            <p class="text-muted mb-0">Lingkungan belajar kondusif di Kampung Inggris.</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card-custom border-0 shadow-sm h-100 p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="feature-icon me-3" style="width: 50px; height: 50px;">
                                    <i class="bi bi-grid"></i>
                                </div>
                                <h5 class="fw-bold mb-0">Kelas Variatif</h5>
                            </div>
                            <p class="text-muted mb-0">Pilihannya reguler, intensif, private, maupun persiapan ujian.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="mb-5">
                <div class="card-custom bg-primary border-0 shadow-sm p-5 text-center text-white">
                    <h2 class="fw-bold mb-3">Jangan Hanya Bermimpi, Take Action!</h2>
                    <p class="lead mb-4">Kuasai bahasa Asing dengan belajar bersama SOS Course & Training di Kampung Inggris Pare.</p>
                    <p class="mb-4">Setiap kelas adalah langkah nyata untuk membuka peluang studi ke luar negeri, karier global, hingga pengembangan diri yang lebih percaya diri.</p>
                    <a href="#" class="btn btn-light btn-lg fw-bold px-5 py-3">
                        <i class="bi bi-calendar-check me-2"></i>Daftar Sekarang
                    </a>
                </div>
            </section>
        </div>
    </div>
</div>

<style>
    section {
        scroll-margin-top: 100px;
    }
    
    .list-group-item.active {
        background-color: var(--dark-red);
        border-color: var(--dark-red);
    }
    
    #about-nav .list-group-item:hover {
        background-color: var(--light-red);
        color: var(--dark-red);
    }
    
    .scroll-mt {
        transition: all 0.3s;
    }
    
    .bg-pink {
        background-color: #ff6b9d !important;
    }
</style>

<script>
    document.querySelectorAll('#about-nav a').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            document.querySelector(targetId).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
</script>
<?= $this->endSection() ?>
