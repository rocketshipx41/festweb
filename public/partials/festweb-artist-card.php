<div class="card">
    <div class="card-body">
        <h4 class="card-title"><?= $artist->artist; ?></h4>
        <img src="<?= $artist_img_path . $artist->photo; ?>" alt="<?= $artist->artist; ?>"/>
        <p><?= smart_trim($artist->artist_description, 150); ?></p>
        <ul><?= $artist->personnel; ?></ul>
        <p><a href="<?= esc_url(add_query_arg(array('artist' => $artist->artist_slug),
                home_url('/artist-profile/'))); ?>">See more</a></p>
    </div>
</div>
