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

<div class="pagetitle mb-3">
    <h1>Manajemen Pembelian (Transaksi)</h1>
</div>

<div class="card">
    <div class="card-body pt-3">
        <table class="table datatable">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Pelanggan</th>
                    <th scope="col">Alamat</th>
                    <th scope="col">Ongkir</th>
                    <th scope="col">Total Harga</th>
                    <th scope="col">Status</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $index => $transaction): ?>
                    <tr>
                        <th scope="row"><?= $index + 1 ?></th>
                        <td><?= esc($transaction['username']) ?></td>
                        <td><?= esc($transaction['alamat']) ?></td>
                        <td><?= number_to_currency($transaction['ongkir'], 'IDR') ?></td>
                        <td><?= number_to_currency($transaction['total_harga'], 'IDR') ?></td>
                        <td>
                            <?php if ($transaction['status'] == 1): ?>
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Sudah Selesai</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i> Belum Selesai</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d-m-Y H:i', strtotime($transaction['created_at'])) ?></td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#detailModal-<?= $transaction['id'] ?>">
                                <i class="bi bi-info-circle"></i> Detail
                            </button>
                            
                            <?= form_open(base_url('pembelian/status/' . $transaction['id']), ['class' => 'd-inline']); ?>
                            <?= csrf_field(); ?>
                            <button type="submit" class="btn btn-warning btn-sm text-dark">
                                <i class="bi bi-arrow-left-right"></i> Ubah Status
                            </button>
                            <?= form_close(); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Detail Modals -->
<?php foreach ($transactions as $transaction): ?>
    <div class="modal fade" id="detailModal-<?= $transaction['id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 580px;">
            <div class="modal-content" style="border-radius: 12px; box-shadow: 0 8px 30px rgba(0,0,0,0.15);">
                <div class="modal-header" style="padding: 20px 28px;">
                    <h5 class="modal-title" style="font-size: 1.25rem; font-weight: 600;">Detail Transaksi #<?= $transaction['id'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="text-align: left; padding: 24px 28px;">
                    <?php 
                    $details = $products[$transaction['id']] ?? [];
                    if (!empty($details)):
                        foreach ($details as $index => $detail):
                    ?>
                        <p class="mb-1" style="font-size: 1.05rem; color: #212529; font-weight: 500;"><?= $index + 1 ?>)</p>
                        <div class="mb-2">
                            <?php if ($detail['foto'] != '' && file_exists("img/" . $detail['foto'])): ?>
                                <img src="<?= base_url('img/' . $detail['foto']) ?>" style="width: 120px; height: 120px; object-fit: contain; padding: 6px; border: 1px solid #dee2e6; border-radius: 8px;">
                            <?php else: ?>
                                <div style="width: 120px; height: 120px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #dee2e6; border-radius: 8px; background-color: #f8f9fa; color: #6c757d; font-size: 0.9rem;">No Image</div>
                            <?php endif; ?>
                        </div>
                        <p class="mb-1" style="font-size: 1.05rem; color: #212529;"><strong><?= esc($detail['nama']) ?></strong> IDR <?= number_format($detail['harga_satuan'] ?? $detail['harga'], 0, ',', '.') ?></p>
                        <p class="mb-1" style="font-size: 1.05rem; color: #212529;">(<?= $detail['jumlah'] ?> pcs)</p>
                        <p class="mb-3" style="font-size: 1.05rem; color: #212529;">IDR <?= number_format($detail['subtotal_harga'], 0, ',', '.') ?></p>
                        <hr style="border-top: 1px solid #dee2e6; margin: 20px 0;">
                    <?php 
                        endforeach;
                    endif; 
                    ?>
                    <p class="mb-0" style="font-size: 1.05rem; color: #212529; font-weight: 600;">Ongkir IDR <?= number_format($transaction['ongkir'], 0, ',', '.') ?></p>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?= $this->endSection() ?>
