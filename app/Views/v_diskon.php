<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<!-- Notification Alerts -->
<?php if (session()->getFlashData('success')): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashData('failed')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('failed') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashData('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            <?php foreach (session()->getFlashData('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="pagetitle mb-3">
    <h1>Manajemen Diskon Harian</h1>
</div>

<div class="mb-3">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bi bi-plus-circle me-1"></i> Tambah Diskon
    </button>
</div>

<div class="card">
    <div class="card-body pt-3">
        <table class="table datatable">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Nominal Diskon (per Item)</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($discounts as $index => $discount): ?>
                    <tr>
                        <th scope="row"><?= $index + 1 ?></th>
                        <td><?= date('d-m-Y', strtotime($discount['tanggal'])) ?></td>
                        <td><?= number_to_currency($discount['nominal'], 'IDR') ?></td>
                        <td>
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editModal-<?= $discount['id'] ?>">
                                <i class="bi bi-pencil-square"></i> Ubah
                            </button>
                            <a href="<?= base_url('diskon/delete/' . $discount['id']) ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus diskon tanggal <?= date('d-m-Y', strtotime($discount['tanggal'])) ?> ini?')">
                                <i class="bi bi-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Diskon Harian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open(base_url('diskon/create')); ?>
            <?= csrf_field(); ?>
            <div class="modal-body">
                <div class="mb-3">
                    <?= form_label('Tanggal', 'tanggal', ['class' => 'form-label']); ?>
                    <?= form_input([
                        'type' => 'date',
                        'name' => 'tanggal',
                        'id' => 'tanggal',
                        'class' => 'form-control',
                        'value' => date('Y-m-d'),
                        'required' => true
                    ]); ?>
                </div>
                <div class="mb-3">
                    <?= form_label('Nominal Diskon (IDR)', 'nominal', ['class' => 'form-label']); ?>
                    <?= form_input([
                        'type' => 'number',
                        'name' => 'nominal',
                        'id' => 'nominal',
                        'class' => 'form-control',
                        'placeholder' => 'Contoh: 10000',
                        'required' => true,
                        'min' => '1'
                    ]); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <?= form_submit('submit', 'Simpan', ['class' => 'btn btn-primary']); ?>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- Edit Modals -->
<?php foreach ($discounts as $discount): ?>
    <div class="modal fade" id="editModal-<?= $discount['id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Diskon Harian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?= form_open(base_url('diskon/edit/' . $discount['id'])); ?>
                <?= csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <?= form_label('Tanggal', 'tanggal-' . $discount['id'], ['class' => 'form-label']); ?>
                        <?= form_input([
                            'type' => 'date',
                            'name' => 'tanggal',
                            'id' => 'tanggal-' . $discount['id'],
                            'class' => 'form-control-plaintext border-bottom',
                            'value' => $discount['tanggal'],
                            'readonly' => true
                        ]); ?>
                    </div>
                    <div class="mb-3">
                        <?= form_label('Nominal Diskon (IDR)', 'nominal-' . $discount['id'], ['class' => 'form-label']); ?>
                        <?= form_input([
                            'type' => 'number',
                            'name' => 'nominal',
                            'id' => 'nominal-' . $discount['id'],
                            'class' => 'form-control',
                            'value' => $discount['nominal'],
                            'required' => true,
                            'min' => '1'
                        ]); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <?= form_submit('submit', 'Simpan', ['class' => 'btn btn-primary']); ?>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?= $this->endSection() ?>
