<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<?php
if (session()->getFlashData('success')) {
?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
?>
<div class="row">
    <?php foreach ($products as $key => $item): ?>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <img src="<?= base_url() . "img/" . $item['foto'] ?>" alt="..." width="50%">
                    <h5 class="card-title">
                        <?= $item['nama'] ?><br>
                        <?php if (session()->has('discount_today')): 
                            $discountedPrice = max(0, $item['harga'] - session()->get('discount_today'));
                        ?>
                            <s style="color: red;"><?= number_to_currency($item['harga'], 'IDR') ?></s><br>
                            <?= number_to_currency($discountedPrice, 'IDR') ?>
                        <?php else: ?>
                            <?= number_to_currency($item['harga'], 'IDR') ?>
                        <?php endif; ?>
                    </h5>
                    <?= form_open('keranjang') ?>
                    <?= form_hidden([
                        'id'    => (string) $item['id'],
                        'nama'  => (string) $item['nama'],
                        'harga' => (string) (session()->has('discount_today') ? max(0, $item['harga'] - session()->get('discount_today')) : $item['harga']),
                        'foto'  => (string) $item['foto']]) ?>
                    <button type="submit" class="btn btn-info rounded-pill">Beli</button>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>
<?= $this->endSection() ?>