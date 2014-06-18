<?php 
defined('C5_EXECUTE') or die("Access Denied.");
/**
 * @author Fumito MIZUNO <mizuno@rescuework.jp>
 * @license MIT
 */
$html = Loader::helper('html');
$this->addHeaderItem($html->css('jquery.datetimepicker.css', 'loginhistory'));
$this->addHeaderItem($html->javascript('jquery.datetimepicker.js', 'loginhistory'));
$help_text = t('By clicking uID  Date/Time, IP, or User Agent on top of the table, you can sort. ') .
            t('Another click will reverse the ordering. ');
            echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Login History'), $help_text, false, false);

?>


<div class="ccm-pane-body">

    <form method="get" id="ccm-log-search" class="form-horizontal" action="<?php  echo $pageBase?>">

        <fieldset>
            <div class="control-group">
                <?php 
                echo $form->label('itemsperpage',t('Number of Histories: '));
                ?>
                <div class="controls">
                    <?php 
                    echo $form->select('itemsperpage',
                        array(10=>10,20=>20,50=>50,500=>500),
                        array('style'=>'width:150px;')
                    )
                    ?>
                </div>
                <?php 
                echo $form->label('from',t('From: '));
                ?>
                <div class="controls">
                    <?php 
                    echo $form->text('from','',array('style'=>'width:150px;'))
                    ?>
                </div>
                <?php 
                echo $form->label('to',t('To: '));
                ?>
                <div class="controls">
                    <?php 
                    echo $form->text('to','',array('style'=>'width:150px;'))
                    ?>
                </div>
            </div>
            <script>
                $(function() {
                    $( "#from" ).datetimepicker({
                        maxDate: 0,
                        format:'Y-m-d H:00:00'
                    });
                    $( "#to" ).datetimepicker({
                        format:'Y-m-d H:00:00',
                        maxDate: 0
                    });
                });
            </script>
            <?php 
            echo $form->label('user',t('Select a user: '));
            ?>

            <div class="controls">
                <div id="userlist">
                    <?php 
                    foreach($users as $ui) {
                        echo $form->checkbox('user[]',$ui->getUserID());
                        echo $ui->getUserName();
                    }
                    ?>
                    <?php 
                    if ($pagination->number_of_pages >= $pagination->current_page+2) {
                        $next = true;
                    } else {
                        $next = false;
                    }
                    if ($next) {?>
                        <div class="linkurl"><input type="hidden" class="morelinkurl" value="<?php  echo $pagination->current_page+2;?>"></div>
                    <?php  }?>
                </div>
                <div id="nextbutton">
                    <?php  if ($next) {?>
                        <input type="button" class="morelink" value="<?php  echo t('Show More Users');?>">
                    <?php  } ?>
                </div>
            </div>



            <script>
                $(document).ready(function($){
                    $(".morelink").live('click', function(){
                        $.ajax({
                            url: '?ccm_paging_p='+$(".morelinkurl").val(),
                            type: 'GET',
                            timeout: 2000,
                            success: function(result, textStatus, xhr) {
                                $('.linkurl').remove();
                                // parseHTML preferable but not available in jQuery1.7
                                $("#nextbutton").html($(result).find("#nextbutton").html());
                                $("#userlist").append($(result).find("#userlist").html());
                            },
                            error: function(result, textStatus, xhr) {
                                // failed to get data;
                            }
                        });
                    });
                });
            </script>
        </fieldset>
        <div class="controls">
            <?php   echo $form->submit('search',t('Search'),array('class'=>'btn primary') );?>
        </div>

    </form>

    <table class="table table-bordered ccm-results-list">
        <thead>
        <tr>
            <th class="subheader"><a href="<?php  echo $loginhistorylist->getSortByURL('uID');?>"><?php   echo t('uID');?></a></th>
            <th class="subheader"><a href="<?php  echo $loginhistorylist->getSortByURL('loginTime');?>"><?php   echo t('Date/Time');?></a></th>
            <th class="subheader"><a href="<?php  echo $loginhistorylist->getSortByURL('IP');?>"><?php   echo t('IP');?></a></th>
            <th class="subheader"><a href="<?php  echo $loginhistorylist->getSortByURL('userAgent');?>"><?php   echo t('User Agent');?></a></th>
        </tr>
        </thead>
        <tbody>
        <?php   foreach($logins as $ent) { ?>
            <tr>
                <td valign="top" style="white-space: nowrap" class="active"><?php   echo $ent['uID'];?></td>
                <td valign="top" style="white-space: nowrap" class="active"><?php   echo $ent['loginTime'];?></td>
                <td valign="top" style="white-space: nowrap" class="active"><?php   echo htmlspecialchars($ent['IP'],ENT_QUOTES,APP_CHARSET);?></td>
                <td valign="top" class="active"><?php  echo htmlspecialchars($ent['userAgent'],ENT_QUOTES,APP_CHARSET);?></td>
            </tr>
        <?php     } ?>
        </tbody>
    </table>


</div>











<!-- END Body Pane -->


<?php    echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false);?>

