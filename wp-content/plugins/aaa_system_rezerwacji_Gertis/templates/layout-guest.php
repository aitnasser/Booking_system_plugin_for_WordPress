<div class="wrap">
    <br />

    <?php if($this->hasFlashMsg()): ?>
        <div id="message" class="<?php echo $this->getFlashMsgStatus(); ?>">
            <p><?php echo $this->getFlashMsg(); ?></p>
        </div>
    <?php endif; ?>

    <h2>
        <a href="<?php echo $this->getAdminPageUrl('-guests'); ?>">Gertis System Rezerwacji - Uczestnicy</a>

        <?php if (!empty($_GET['event_turn'])): ?>

            <?php $Model = new Gertis_BookingSystem_Model(); ?>

            <?php if ($Model->checkEventTurn($_GET['event_turn'])): ?>
                <a class="add-new-h2" href="<?php echo $this->getAdminPageUrl('-guests', array('view' => 'guest-form', 'event_turn' => $_GET['event_turn'])); ?>">Dodaj nowego uczestnika do obozu <?php echo $_GET['event_turn']; ?></a>
            <?php endif; ?>

        <?php else: ?>
            <a class="add-new-h2" href="<?php echo $this->getAdminPageUrl('-guests', array('view' => 'guest-form')); ?>">Dodaj nowego uczestnika</a>
        <?php endif; ?>

    </h2>

    <br /><br />

<?php require_once $view; ?>


<br style="clear: both;">

</div>