<?php
/**
 * @link https://vcode-studio.com/
 *
 * @package Vcode-studio
 */

get_header();
?>
<div class="page-wrap row gap-32 detail">
    <?= comp("slider-banner",["imgs"=>_acf("imgs")?_acf("imgs"):[_acf("thumb")]]) ?>
    <div class="col col-2 gap-32">
        <div class="row gap-1r bio">
            <h3><?= get_the_title() ?></h3>
            <p class="year"><?= _acf("profile_year") ?></p>
            <?php if(!_acf("artist") || empty(_acf("artist"))): ?>
                <p><?= _acf("artist_str") ?></p>
                <?php else: ?>
                <p><?= map_artist(_acf("artist")) ?></p>
            <?php endif; ?>
            <?php
            if(is_admin()):
                if($f=_acf("profile_id")):
            ?>
            <hr style="width:50%"/>
            <div class="col gap-1r meta">
                <p class="bold">분류번호</p>
                <p><?= $f ?></p>
            </div>
            <?php
                endif;
                if($f=_acf("profile_location")):
            ?>
            <div class="col gap-1r meta">
                <p class="bold">보관위치</p>
                <p><?= $f ?></p>
            </div>
            <?php
                endif;
            endif;
            ?>
        </div>
        <div>
            <?php
                if($f=_acf("profile_desc")):
            ?>
            <p>
            <?= $f ?>
            </p>
            <?php
                endif;
            ?>
        </div>
    </div>
    <?php foreach(_acfobjs() as $i => $field):
        if(isValidGroup($field["value"])):
        ?>
    <div class="accordion col col-2 tab-<?= $i ?>">
        <h5 class="title" click="$('.tab-<?= $i ?>').toggleClass('open')">
                <?= $field['label'] ?>
                <?= icon('chevron/down','i_close') ?>
                <?= icon('chevron/up','i_open') ?>
        </h5>
        <div class="row gap-1r">
            <?php switch($field['type']):
                case "group":
            ?>
            <table>
                <tbody>
                <?php foreach($field['value'] as $slug => $value): ?>
                    <?php if((
                    $txt = $field['name'].'_'.$slug)
                    && ($subfield = _acfobj($txt))
                    && _acf($txt)
                    && (!is_array($subfield["value"]) || !empty(array_filter($subfield["value"],"isValid")))
                    ):
                        // var_dump(array_filter($subfield["value"],array_values($subfield["value"])));
                        ?>
                        <tr>
                            <td>
                                <?= $subfield['label'] ?>
                            </td>
                            <td>
                                <?php if($sv = $subfield['value']): ?>
                                <?php switch ($subfield['type']):
                                        case "taxonomy":
                                            switch($subfield['taxonomy']):
                                                case "process":
                                                    $map = array_map(function($a) {return $a->name;},$sv);
                                                    ?>
                                                        <?= implode(", ",$map) ?>
                                                    <?php
                                                break;
                                                case "post_tag":
                                                ?>
                                                    <div class="col gap-1r">
                                                        <?php foreach($sv as $tag): ?>
                                                            <a href="<?= get_term_link($tag) ?>" class="tag"><?= $tag->name ?></a>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php
                                                break;
                                            endswitch;
                                        ?>
                                    <?php break; ?>
                                    <?php case "file": ?>
                                        <a href="<?= $sv['url'] ?>" download><?= mb_strimwidth($sv['name'],0,12,"...").'.'.$sv['subtype']."(".formatBytes($sv['filesize'],1).")" ?></a>
                                    <?php break; ?>
                                    <?php case "repeater": ?>
                                        <div class="row gap-1fr">
                                        <?php foreach($sv as $s): ?>
                                            <div class="sub-table col">
                                                <div><?= $s['label'] ?></div>
                                                <div><?= $s['size'] ?></div>
                                            </div>
                                            <?php endforeach; ?>
                                            </div>
                                    <?php break; ?>
                                    <?php case "group":
                                                ?>
                                                <div class="row gap-1fr">
                                                    <?php if($field['name'] == 'copywrite'):
                                                            foreach($sv as $ssk=>$ssv_):
                                                                if($ssv = get_field_object($subfield['name']."_".$ssk) && $ssv_ && !empty($ssv_)):
                                                        ?>
                                                        <div class="sub-table col">
                                                                <div>
                                                                    <?= $ssv['label'] ?>
                                                                </div>
                                                                <div>
                                                                    <?php switch($ssv['type']):
                                                                    case "image":
                                                                    ?>
                                                                    <a href="<?= $ssv_['url'] ?>" download>
                                                                        <?= mb_strimwidth($ssv_['title'] ?? "",0,12,"...")
                                                                        .'.'.$ssv_['subtype'] ?? ""
                                                                        ."(".formatBytes($ssv_['filesize'] ?? "",1).")"
                                                                        ?>
                                                                    </a>
                                                                    <?php
                                                                    break;
                                                                    case "file":
                                                                    ?>
                                                                        <a href="<?= $ssv_['url'] ?>" download><?= mb_strimwidth($ssv_['name'],0,12,"...").'.'.$ssv_['subtype']."(".formatBytes($ssv_['filesize'],1).")" ?></a>
                                                                    <?php
                                                                    break;
                                                                    default:
                                                                    ?>
                                                                    <?= $ssv_ ?>
                                                                    <?php endswitch;?>
                                                                </div>
                                                        </div>
                                                    <?php endif; endforeach;
                                                endif; ?>
                                                </div>
                                                <?php
                                                break;
                                default: ?>
                                        <?= $sv ?>
                                <?php endswitch; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php break; ?>
            <?php case "taxonomy": ?>
                <?php if(($svs = $field['value']) && $field['taxonomy'] == 'artist'): ?>
                <?php
                    foreach($svs as $sv):
                ?>
                <div class="row gap-12">
                    <h4><?= $sv->name ?></h4>
                    <p><?= get_field('year',$sv) ?></p>
                    <p><?= $sv->description ?></p>
                </div>
                <?php
                    endforeach;        
                ?>                           
                <?php endif; ?>
            <?php break; ?>
            <?php endswitch;?>
        </div>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>
    
    <a href="<?= wp_get_referer()?wp_get_referer():'/' ?>">
        <button>
            목록으로
        </button>
    </a>
</div>
<?php
get_footer();