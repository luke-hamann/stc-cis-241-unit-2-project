<?php
/**
 * Title: Song Form
 * Purpose: To provide a form for adding or updating a song
 */
?>

<?php include('views/_partials/header.php'); ?>

<h1>
    <?php echo ($song['id'] == 0 ? 'Add' : 'Edit') . ' Song'; ?>
</h1>

<form action="." method="post" class="form">
    <?php include('views/_partials/formErrors.php'); ?>

    <input type="hidden" name="action" value="editSong" />
    <input type="hidden" name="songId" value="<?php echo $song['id']; ?>" />

    <div class="form-group">
        <label for="name" class="label">Name</label>
        <input type="text" name="name" id="name" class="input"
            value="<?php echo htmlspecialchars($song['name']); ?>" autofocus />
    </div>

    <div>
        <label for="albumId" class="label">Album</label>
        <select name="albumId" class="select">
            <option value="0">-</option>
            <?php foreach ($albums as $album) : ?>
                <option value="<?php echo $album['id']; ?>"
                    <?php echo ($song['albumId'] == $album['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($album['name']) . ' (' .
                        htmlspecialchars($album['artistName']) . ')'; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <?php
        $length = $song['length'];
        $minutes = intdiv($length, 60);
        $seconds = $length - ($minutes * 60);
    ?>

    <div class="form-group length-group">
        <label for="minutes" class="mr-2 label">Length</label>
        <input type="number" name="minutes" id="minutes" class="input" 
            value="<?php echo $minutes; ?>" />
        <label for="seconds" class="label">:</label>
        <input type="number" name="seconds" id="seconds" class="input" 
            value="<?php echo $seconds; ?>" />
    </div>

    <div class="form-group check-boxes mt-3">
        <label class="label">Artists</label>
        <?php foreach ($artists as $artist) : ?>
            <div class="form-check">
                <input type="checkbox" name="artistIds[<?php echo $artist['id']; ?>]"
                    class="form-check-input"
                    <?php echo (in_array($artist['id'], $artistIds) ? 'checked' : ''); ?> />
                <label class="label form-check-label">
                    <?php echo htmlspecialchars($artist['name']); ?>
                </label>
            </div>
        <?php endforeach; ?>
    </div>

    <div>
        <input type="submit" value="Submit" class="btn btn-submit" />

        <?php
            if ($song['id'] == 0)
            {
                $href = '?action=listSongs';
            }
            else
            {
                $href = "?action=viewSong&songId=" . $song['id'];
            }
        ?>

        <a href="<?php echo $href; ?>" class="btn btn-cancel">
            Cancel
        </a>
    </div>
</form>

<?php include('views/_partials/footer.php'); ?>
