<?php $Model = new Gertis_BookingSystem_Model();

$event_code = (isset($_GET['event_code'])) ? $_GET['event_code'] : 'All';
$event_code_list = $Model->getEventCodeList();
$page_name = $_GET['page'];

if($page_name == 'gertis-book-system'){
    $archive = false;
    $adminPageName = '';
}
else{
    $archive = true;
    $adminPageName = '-archive';
}

?>


<!--Filtrowanie listy z kodami imprez-->
<?php if(!$archive): ?>
    <ul class="subsubsub">
        <?php foreach ($event_code_list as $event): ?>
            <li>
                <a <?php echo ($event_code == $event['event_code']) ?  'class="current"' : ''; ?>
                    href="<?php echo $this->getAdminPageUrl($adminPageName, array('event_code' => $event['event_code'])); ?>" >
                    <?php echo substr($event['event_code'], 0, -5); ?>
                </a>
            </li> |
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<br />
<br />
<br />

<form method="get" action="<?php echo $this->getAdminPageUrl($adminPageName); ?>" id="gertis-events-form-1">

    <input type="hidden" name="page" value="<?php echo static::$plugin_id.$adminPageName; ?>" />
    <input type="hidden" name="paged" value="<?php echo $Pagination->getCurrPage(); ?>" />

    Sortuj według
    <select name="orderby">
        <?php foreach(Gertis_BookingSystem_Model::getEventOrderByOpts() as $key=>$val): ?>
            <option
                <?php echo($val == $Pagination->getOrderBy()) ? 'selected="selected"' : ''; ?>
                value="<?php echo $val; ?>">
                <?php echo $key; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="orderdir">
        <?php if($Pagination->getOrderDir() == 'asc') :?>
            <option selected="selected" value="asc">Rosnąco</option>
            <option value="desc">Majeląco</option>
        <?php else: ?>
            <option value="asc">Rosnąco</option>
            <option selected="selected" value="desc">Majeląco</option>
        <?php endif; ?>
    </select>

    <input type="submit" class="button-secondary" value="Sortuj" />

    <div class="alignright actions">
        <input type="search" id="post-search-input" name="search" value="">
        <input type="submit" id="search-submit" class="button" value="Szukaj usługi">
    </div>

</form>


<form action="<?php echo $this->getAdminPageUrl($adminPageName, array('view' => 'events', 'action' => 'bulk')); ?>" method="post" id="gertis-events-form-2" onsubmit="return confirm('Czy na pewno chcesz zastosować zmiany masowe?')">

    <?php wp_nonce_field($this->action_token.'bulk'); ?>

    <div class="tablenav">

        <div class="alignleft actions bulkactions">

            <select name="bulkaction">
                <option value="0">Masowe działania</option>
                <option value="delete">Usuń</option>
                <option value="actual">Opublikowany</option>
                <option value="no_actual">Nieopublikowany</option>
            </select>

            <input type="submit" class="button-secondary" value="Zastosuj" />

        </div>

<!--        <div class="alignleft actions">-->
<!---->
<!--            <select name="filter">-->
<!--                <option value="0">Wszystkie imprezy</option>-->
<!--                <option value="pos">POS</option>-->
<!--                <option value="dem">DEM</option>-->
<!--                <option value="bjk">BJK</option>-->
<!--            </select>-->
<!---->
<!--            <input type="submit" class="button-secondary" value="Filtruj" />-->
<!---->
<!--        </div>-->

        <div class="tablenav-pages">
            <span class="displaying-num"><?php  echo $Pagination->getTotalCount(); ?> imprezy</span>

            <?php
            $curr_page = $Pagination->getCurrPage();
            $last_page = $Pagination->getLastPage();

            $first_disabled = '';
            $last_disabled = '';

            $url_params = array(
                'orderby' => $Pagination->getOrderBy(),
                'orderdir' => $Pagination->getOrderDir()
            );


            $url_params['paged'] = 1;
            $first_page_url = $this->getAdminPageUrl($adminPageName, $url_params);

            $url_params['paged'] = $curr_page-1;
            $prev_page_url = $this->getAdminPageUrl($adminPageName, $url_params);

            $url_params['paged'] = $last_page;
            $last_page_url = $this->getAdminPageUrl($adminPageName, $url_params);

            $url_params['paged'] = $curr_page+1;
            $next_page_url = $this->getAdminPageUrl($adminPageName, $url_params);


            if($curr_page == 1){
                $first_page_url = '#';
                $prev_page_url = '#';

                $first_disabled = 'disabled';
            }
            if($curr_page == $last_page){
                $last_page_url = '#';
                $next_page_url = '#';

                $last_disabled = 'disabled';
            }
            ?>

            <span class="pagination-links">
                <a href="<?php echo $first_page_url; ?>" title="Idź do pierwszej strony" class="first-page <?php echo $first_disabled; ?>">«</a>&nbsp;&nbsp;
                <a href="<?php echo $prev_page_url; ?>" title="Idź do poprzedniej strony" class="prev-page <?php echo $first_disabled; ?>">‹</a>&nbsp;&nbsp;
                
                <span class="paging-input"><?php echo $curr_page ?>  z <span class="total-pages"><?php echo $last_page ?></span></span>
                
                &nbsp;&nbsp;<a href="<?php echo $next_page_url; ?>" title="Idź do następnej strony" class="next-page <?php echo $last_disabled; ?>">›</a>
                &nbsp;&nbsp;<a href="<?php echo $last_page_url; ?>" title="Idź do ostatniej strony" class="last-page <?php echo $last_disabled; ?>">»</a>
                
            </span>
        </div>

        <div class="clear"></div>

    </div>


    <table class="widefat">
        <thead>
        <tr>
            <th class="check-column"><input type="checkbox" /></th>
            <th>ID</th>
            <th>Kod wydarzenia</th>
            <th>Turnus</th>
            <th>Początek</th>
            <th>Koniec</th>
            <th>Cena</th>
            <th>Liczba miejsc</th>
            <th>Zapisanych</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody id="the-list">

        <?php if($Pagination->hasItems()): ?>

            <?php foreach($Pagination->getItems() as $i=>$item): ?>

                <tr <?php echo ($i%2) ? 'class="alternate"' : ''; ?>>
                    <th class="check-column">
                        <input type="checkbox" value="<?php echo $item['id']; ?> 1" name="bulkcheck[]" />
                    </th>
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo $item['event_code']; ?>
                        <div class="row-actions">
                                <span class="edit">
                                    <a class="edit" href="<?php echo $this->getAdminPageUrl('', array('view' => 'event-form', 'eventid' => $item['id'])); ?>">Edytuj</a>
                                </span> |
                                <span class="trash">
                                    <?php
                                    $token_name = $this->action_token.$item['id'];
                                    $del_url = $this->getAdminPageUrl('', array('action' => 'delete', 'eventid' => $item['id']));
                                    ?>
                                    <a class="delete" href="<?php echo wp_nonce_url($del_url, $token_name) ?>" onclick="return confirm('Czy na pewno chcesz usunąć tego uczestnika?')">Usuń</a>
                                </span> |
                                <span class="edit">
                                    <a class="edit" href="<?php echo $this->getAdminPageUrl('-guests', array('action' => 'members', 'event_turn' => $item['event_turn'])); ?>">Uczestnicy</a>
                                </span> |
                                <span class="edit">
                                        <a class="edit" href="<?php echo $this->getAdminPageUrl('-guests', array('view' => 'guests-export', 'event_turn' => $item['event_turn'], 'event_date' => $Model->getEventDate($item['event_turn']))); ?>">Export uczestników</a>
                                </span>
                        </div>
                    </td>
                    <td><?php echo $item['event_turn']; ?></td>
                    <td><?php $start_date = date_create($item['start_date']); echo date_format($start_date, 'd-m-Y');?></td>
                    <td><?php $end_date = date_create($item['end_date']); echo date_format($end_date, 'd-m-Y');?></td>
                    <td><?php echo $item['price']; ?></td>
                    <td><?php echo $item['seat_no']; ?></td>
                    <td><?php echo $item['taken_seats']; ?></td>
                    <td>
                        <?php
                            if($item['status'] == 'yes'){
                                echo 'opublikowany';
                            }
                            else if($item['status'] == 'no'){
                                echo 'nieopublikowany';
                            }
                            else{
                                echo 'zakończony';
                            }
                        ?>
                    </td>
                </tr>

            <?php endforeach; ?>


        <?php else: ?>
            <tr>
                <td colspan="8">Brak rejsów w bazie danych</td>
            </tr>
        <?php endif; ?>
        </tbody>

    </table>


    <div class="tablenav">
        <div class="tablenav-pages">
            
            <span class="pagination-links">
                Przejdź do strony

                <?php

                $url_params = array(
                    'orderby' => $Pagination->getOrderBy(),
                    'orderdir' => $Pagination->getOrderDir()
                );

                for($i=1; $i<=$Pagination->getLastPage(); $i++){

                    $url_params['paged'] = $i;
                    $url = $this->getAdminPageUrl($adminPageName, $url_params);

                    if($i == $Pagination->getCurrPage()){
                        echo "&nbsp;<strong>{$i}</strong>";
                    }else{
                        echo '&nbsp;<a href="'.$url.'">'.$i.'</a>';
                    }

                }
                ?>
            </span>

        </div>

        <div class="clear"></div>
    </div>

</form>