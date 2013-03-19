<?
class Up2social_Soshake_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{
        public function indexAction()
        {
                //Pour éviter la création de fichier template, pouvant être écrasé, nous générons le contenu ici
                $base_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
                $nom = ''.$this->getRequest()->getPost('up2-text');
                
                if( $this->getRequest()->getPost() != array() ) {
                        $collection = Mage::getModel('soshake/soshake')->getCollection();
                        if($this->getRequest()->getPost('up2-hook') != "") {
                                $collection->addFilter('hook',array('eq' => $this->getRequest()->getPost('up2-hook')));
                                $collection->addFilter('BorA',array('eq' => $this->getRequest()->getPost('up2-where')));
                                foreach($collection as $data) { $data->delete(); }
                                foreach($this->getRequest()->getPost('up2-SoShake') as $feature) {
                                        $hook = Mage::getModel('soshake/soshake');
                                        $hook->setData('hook',$this->getRequest()->getPost('up2-hook'));
                                        $hook->setData('BorA',$this->getRequest()->getPost('up2-where'));
                                        $hook->setData('feature',$feature);
                                        $hook->save();
                                }
                                $update->hook = true;
                        } elseif($this->getRequest()->getPost('up2-text-intro') != "") {
                                $collection->addFilter('hook',array('eq' => "up2-text-intro"));
                                foreach($collection as $data) { $data->delete(); }
                                $hook = Mage::getModel('soshake/soshake');
                                $hook->setData('hook',"up2-text-intro");
                                $hook->setData('feature',$this->getRequest()->getPost('up2-text-intro'));
                                $hook->save();
                                
                                $update->text = true;
                        } else {
                                $collection->addFilter('hook',array('eq' => "align"));
                                foreach($collection as $data) { $data->delete(); }
                                $hook = Mage::getModel('soshake/soshake');
                                $hook->setData('hook',"align");
                                $hook->setData('feature',$this->getRequest()->getPost('up2-align'));
                                $hook->save();
                                
                                $collection = Mage::getModel('soshake/soshake')->getCollection();
                                $collection->addFilter('hook',array('eq' => "position"));
                                foreach($collection as $data) { $data->delete(); }
                                $hook = Mage::getModel('soshake/soshake');
                                $hook->setData('hook',"position");
                                $hook->setData('feature',$this->getRequest()->getPost('up2-position'));
                                $hook->save();
                                $update->layout = true;
                        }
                }

                $content = "";
                $content .= '
                        <style type="text/css">
                                h1 {}
                                .up2-confirmation {width:100%;text-align:left;font-weight:bold;padding:5px;font-size:10pt;margin-bottom:15px;border:solid 1px green;background-color:#7FE9A1;}
                                
                                .up2-overlay {position:fixed;top:0px;left:0px;width:100%;height:100%;background:#000;display:none;}
                                .up2-close {display:none;position:fixed;top:180px;z-index:99999999;}
                                .up2-form {position:fixed;width:600px;background:#fff;margin-left:auto;margin-right:auto;top:200px;display:none;z-index:9999999;padding:15px 25px 50px 25px;left:180px;}
                                .up2-button, .up2-button:hover, input.submit {background-color:#5364a7;color:#FFF;font-weight:bold;font-size:10pt;padding:5px 8px;line-height:35px;text-decoration:none;cursor:pointer;-moz-border-radius:8px;-webkit-border-radius:8px;}
                                .up2-hasSoShake, .up2-hasSoShake:hover {padding-right:20px;background:url(\'http://up2social.com/features/LikeButton/1.1/images/green.png\') no-repeat #5364a7;background-size:20px 20px;background-position:right;}
                                input.submit {line-height:20px;border:none;margin-top:30px;}
                                input {margin-right:15px;}
                                
                                .up2-page {}
                                .up2-page p {color:#ccc;}
                                .up2-pageProduit {border:solid 2px;width:800px;margin:30px;}
                                .up2-header {border-bottom:solid 1px;height:60px;padding:10px 0px 0px 10px;}
                                .up2-page-content {width:100%;display:table;padding-top:30px;}
                                .up2-colProduit {display:table-cell;width:70%;margin-right:20px;padding:10px;}
                                
                                .up2-info {display:table;width:100%;}
                                .up2-media {display:table-cell;width:50%;height:200px;border:solid 1px;padding:5px;}
                                .up2-data {display:table-cell;padding:5px;}
                                
                                .up2-details {margin-top:30px;}
                                
                                .up2-sidebar {display:table-cell;}
                                .up2-bloc {border:solid 1px; margin:5px; height:100px;}
                                
                                .up2-content {display:none;}
                                
                                ul.up2-menu {background:url(\'http://up2social.com/front/library/img/logo.png\') no-repeat; background-size:100px 100px;height:100px;vertical-align:middle;padding:30px 0px 38px 100px;display:table;}
                                ul.up2-menu li.up2-item {background-color:#5364a7;color:#fff;display:table-cell;padding:0px 10px;font-weight:bold;text-transform:capitalize;cursor:pointer;border-right:solid 1px;height:30px;padding-top:8px;}
                                ul.up2-menu li.up2-item:hover {background-color:#000;}
                                
                                #copycode p {margin-top:15px;}
                                #copycode input {padding:3px;font-size:11pt;width:600px;}
                        </style>
                        ';
                $content .= "
                <script type=\"text/javascript\" src=\"http://up2social.com/lib/jquery.js\"></script>
                <script type=\"text/javascript\" src=\"http://up2social.com/api/actions/cms/magento.js\"></script>

                <script type=\"text/javascript\">
                        jQuery.noConflict();
                        function up2DisplayForm(hook, BorA, setsSoshakes) {
                                document.getElementById('up2-form-overlay').style.left = window.innerWidth * 0.5 - 300 + 'px';
                                document.getElementById('up2-close').style.left = window.innerWidth * 0.5 + 330 + 'px';
                                
                                document.getElementById('hook').value = hook;
                                document.getElementById('where').value = BorA;
                                
                                for(var i = 0; i<5;i++) {
                                        if(setsSoshakes[i] == 1) document.getElementById('up2-box-' + i).checked = 1;
                                        else document.getElementById('up2-box-' + i).checked = 0;
                                }
                                
                                jQuery('.up2-form').fadeIn(400);
                                jQuery('.up2-close').fadeIn(400);
                                jQuery('.up2-overlay').fadeTo('fast',0.8);
                                jQuery('.up2-overlay').fadeIn(400);
                        }
                        
                        function up2CleanScreenOverlay() {
                            jQuery('.up2-overlay').fadeOut();
                            jQuery('.up2-close').hide();
                            jQuery('.up2-form').hide();
                        }
                        
                        jQuery(document).ready(function() {
                            jQuery('.up2-overlay').click(function() {
                                up2CleanScreenOverlay();
                            });
                        });
                        jQuery(document).ready(function() {
                            jQuery('.up2-close').click(function() {
                                up2CleanScreenOverlay();
                            });
                        });
                        jQuery(document).ready(function() {
                            jQuery('#menu-ancrage').click(function() {
                                jQuery('#copycode').hide();
                                jQuery('#soshakelayout').hide();
                                jQuery('#ancrage').show();
                                jQuery('#actions').hide();
                            });
                        });
                        jQuery(document).ready(function() {
                            jQuery('#menu-copycode').click(function() {
                                jQuery('#copycode').show();
                                jQuery('#soshakelayout').hide();
                                jQuery('#ancrage').hide();
                                jQuery('#actions').hide();
                            });
                        });
                        jQuery(document).ready(function() {
                            jQuery('#menu-actions').click(function() {
                                jQuery('#copycode').hide();
                                jQuery('#soshakelayout').hide();
                                jQuery('#ancrage').hide();
                                jQuery('#actions').show();
                            });
                        });
                        jQuery(document).ready(function() {
                            jQuery('#menu-soshakelayout').click(function() {
                                jQuery('#copycode').hide();
                                jQuery('#soshakelayout').show();
                                jQuery('#ancrage').hide();
                                jQuery('#actions').hide();
                            });
                        });
                </script>
                ";
                //On récupère les infos du client :
                $client = json_decode(file_get_contents("http://up2social.com/api/me.json?url=$base_url"));
                if($client->code == 200 && $client->result->abonnement) {
                        //Client ok, abonnement en cours
                } elseif($client->code == 200 && !$client->result->abonnement) {
                        $content .= '<div style="width:90%;font-size:12pt;font-weight:bold;color:#000;background:#FF3D42;border:solid 2px #C71118;padding:10px;">Vous n\'avez pas d\'abonnement en cours : <a href="http://up2social.com/front" target="_blank" class="up2-button">Choisissez votre offre sur Up 2 Social</a></div>';
                } else {
                        $content .= '<div style="width:90%;font-size:12pt;font-weight:bold;color:#000;background:#FF3D42;border:solid 2px #C71118;padding:10px;">Vous n\'avez pas créé de compte sur le service Up 2 Social. <a href="http://up2social.com" target="_blank" class="up2-button">Faites le gratuitement en cliquant ici</a></div>';
                }
                
                $content .= '
                <div class="up2-overlay"></div>
                <div class="up2-close" id="up2-close"><img src="http://up2social.com/lib/images/close.png" /></div>
                <ul class="up2-menu">
                        <li class="up2-item" id="menu-ancrage" style="border-left:solid 1px;">Insertion automatique des SoShake</li>
                        <li class="up2-item" id="menu-soshakelayout" style="border-left:solid 1px;">Apparence des SoShake</li>
                        <li class="up2-item" id="menu-copycode">Insertion manuelle des SoShake</li>
                        <li class="up2-item" id="menu-actions">Gérer les actions SoShake</li>
                </ul>
                ';
                
                if($update->hook || $update->layout || $update->text) $content .= '
                <div class="up2-confirmation">Modifications enregistrées</div>
                ';
                
                $content .= '
                <div class="up2-content" id="ancrage">
                        <h1>Gestion des points d\'ancrage des SoShake de Up 2 Social</h1>
                        <h2>Page produit</h2>
                        <div class="up2-form" id="up2-form-overlay">
                                <h2>Quels boutons afficher</h2>
                                <form method="post" action="'.$base_url.'adminsoshake/adminhtml_index" id="up2-form-hook">
                                        <input name="form_key" type="hidden" value="'.Mage::getSingleton('core/session')->getFormKey().'"  />
                                        <input type="hidden" name="up2-hook" id="hook" />
                                        <input type="hidden" name="up2-where" id="where" />
                                        <input type="checkbox" name="up2-SoShake[]" value="like" id="up2-box-0" />Bouton Like<br />
                                        <input type="checkbox" name="up2-SoShake[]" value="tweet" id="up2-box-1" />Bouton Tweet<br />
                                        <input type="checkbox" name="up2-SoShake[]" value="gplus" id="up2-box-2" />Bouton +1<br />
                                        <input type="checkbox" name="up2-SoShake[]" value="linkedin" id="up2-box-5" />Bouton LinkedIn<br />
                                        <input type="checkbox" name="up2-SoShake[]" value="fanbox" id="up2-box-3" />FanBox<br />
                                        <input type="checkbox" name="up2-SoShake[]" value="fbconnect" id="up2-box-4" />Facebook Connect<br />
                                        <input type="submit" name="submit" class="submit" value="Enregistrer ces boutons" />
                                </form>
                        </div>
                        <div class="up2-page up2-pageProduit" style="">
                                <div class="up2-page up2-header" style="">
                                        <h3>Entête</h3>
                                </div>
                                <div class="up2-page up2-page-content" style="">
                                        <div class="up2-page up2-colProduit" style="">
                                                <a href="#" class="up2-button '.$this->displayClass("product.info","before").'" onclick="javascript:up2DisplayForm(\'product.info\',\'before\','.$this->jsArray("product.info","before").');">Insérer des SoShake ici</a><br />
                                                <div class="up2-page up2-info" style="">
                                                        <div class="up2-page up2-media" style="">
                                                                <h3>photo</h3><br /><br /><br /><br /><br /><br /><br /><br />
                                                                <a href="#" class="up2-button '.$this->displayClass("product.info.media","after").'" onclick="javascript:up2DisplayForm(\'product.info.media\',\'after\','.$this->jsArray("product.info.media","after").');">Insérer des SoShake ici</a>
                                                        </div>
                                                        <div class="up2-page up2-data" style="">
                                                                <div class="up2-page up2-titre" style="">
                                                                        <h3>Titre Produit</h3>
                                                                </div>
                                                                <a href="#" class="up2-button '.$this->displayClass("product.info.simple","after").'" onclick="javascript:up2DisplayForm(\'product.info.simple\',\'after\','.$this->jsArray("product.info.simple","after").');">Insérer des SoShake ici</a><br />
                                                                <div class="up2-page up2-addto">
                                                                        <h3>Ajouter au Panier</h3>
                                                                </div>
                                                                <a href="#" class="up2-button '.$this->displayClass("product.info.addtocart","after").'" onclick="javascript:up2DisplayForm(\'product.info.addtocart\',\'after\','.$this->jsArray("product.info.addtocart","after").');">Insérer des SoShake ici</a><br />
                                                        </div>
                                                </div>
                                                <a href="#" class="up2-button '.$this->displayClass("product.info","after").'" onclick="javascript:up2DisplayForm(\'product.info\',\'after\','.$this->jsArray("product.info","after").');">Insérer des SoShake ici</a><br />
                                                <div class="up2-page up2-details">
                                                        <div class="up2-page up2-description" style="">
                                                                <a href="#" class="up2-button '.$this->displayClass("product.description","before").'" onclick="javascript:up2DisplayForm(\'product.description\',\'before\','.$this->jsArray("product.description","before").');">Insérer des SoShake ici</a><br />
                                                                <h3>Description Produit</h3>
                                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque pulvinar, lacus a viverra accumsan, ipsum neque dapibus massa, eget tristique libero felis eu ante. Duis ornare sollicitudin lacus quis scelerisque. Nulla facilisi. Sed leo risus, porta nec placerat at, venenatis vel mi. Pellentesque et elit at felis aliquam convallis. Nullam pretium semper condimentum. Nam id velit ac orci placerat iaculis nec et lectus.</p>
                                                                <a href="#" class="up2-button '.$this->displayClass("product.description","after").'" onclick="javascript:up2DisplayForm(\'product.description\',\'after\','.$this->jsArray("product.description","after").');">Insérer des SoShake ici</a><br />
                                                        </div>
                                                        <div class="up2-page up2-attributes" style="">
                                                                <h3>Fiche Technique Produit</h3>
                                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque pulvinar, lacus a viverra accumsan, ipsum neque dapibus massa, eget tristique libero felis eu ante. Duis ornare sollicitudin lacus quis scelerisque. Nulla facilisi. Sed leo risus, porta nec placerat at, venenatis vel mi. Pellentesque et elit at felis aliquam convallis. Nullam pretium semper condimentum. Nam id velit ac orci placerat iaculis nec et lectus.</p>
                                                                <a href="#" class="up2-button '.$this->displayClass("product.attributes","after").'" onclick="javascript:up2DisplayForm(\'product.attributes\',\'after\','.$this->jsArray("product.attributes","after").');">Insérer des SoShake ici</a><br />
                                                        </div>
                                                        <div class="up2-page up2-upsell" style="">
                                                                <h3>Reco de produits liés</h3>
                                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque pulvinar, lacus a viverra accumsan, ipsum neque dapibus massa, eget tristique libero felis eu ante. Duis ornare sollicitudin lacus quis scelerisque. Nulla facilisi. Sed leo risus, porta nec placerat at, venenatis vel mi. Pellentesque et elit at felis aliquam convallis. Nullam pretium semper condimentum. Nam id velit ac orci placerat iaculis nec et lectus.</p>
                                                                <a href="#" class="up2-button '.$this->displayClass("product.info.upsell","after").'" onclick="javascript:up2DisplayForm(\'product.info.upsell\',\'after\','.$this->jsArray("product.info.upsell","after").');">Insérer des SoShake ici</a><br />
                                                        </div>
                                                        <div class="up2-page up2-tags" style="">
                                                                <h3>Tags Produit</h3>
                                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque pulvinar, lacus a viverra accumsan, ipsum neque dapibus massa, eget tristique libero felis eu ante. Duis ornare sollicitudin lacus quis scelerisque. Nulla facilisi. Sed leo risus, porta nec placerat at, venenatis vel mi. Pellentesque et elit at felis aliquam convallis. Nullam pretium semper condimentum. Nam id velit ac orci placerat iaculis nec et lectus.</p>
                                                                <a href="#" class="up2-button '.$this->displayClass("product.product_tag_list","after").'" onclick="javascript:up2DisplayForm(\'product.product_tag_list\',\'after\','.$this->jsArray("product.product_tag_list","after").');">Insérer des SoShake ici</a><br />
                                                        </div>
                                                </div>
                                        </div>
                                        <div class="up2-page up2-sidebar" style="">
                                                <h3>SideBar</h3>
                                                <a href="#" class="up2-button '.$this->displayClass("catalog.product.related","before").'" onclick="javascript:up2DisplayForm(\'catalog.product.related\',\'before\','.$this->jsArray("catalog.product.related","before").');">Insérer des SoShake ici</a><br />
                                                <div class="up2-page up2-relatedProduct up2-bloc" style="">
                                                        <h3>Produit Lié</h3>
                                                </div>
                                                <a href="#" class="up2-button '.$this->displayClass("catalog.compare.sidebar","before").'" onclick="javascript:up2DisplayForm(\'catalog.compare.sidebar\',\'before\','.$this->jsArray("catalog.compare.sidebar","before").');">Insérer des SoShake ici</a><br />
                                                <div class="up2-page up2-compareProduct up2-bloc" style="">
                                                        <h3>Comparateur</h3>
                                                </div>
                                                <a href="#" class="up2-button '.$this->displayClass("cart_sidebar","before").'" onclick="javascript:up2DisplayForm(\'cart_sidebar\',\'before\','.$this->jsArray("cart_sidebar","before").');">Insérer des SoShake ici</a><br />
                                                <div class="up2-page up2-shoppingCart up2-bloc" style="">
                                                        <h3>Panier en cours</h3>
                                                </div>
                                                <a href="#" class="up2-button '.$this->displayClass("cart_sidebar","after").'" onclick="javascript:up2DisplayForm(\'cart_sidebar\',\'after\','.$this->jsArray("cart_sidebar","after").');">Insérer des SoShake ici</a><br />
                                        </div>
                                </div>
                        </div>
                </div>
                ';
                if($update->hook) $content .= '<script type="text/javascript">jQuery(\'#ancrage\').show();</script>';
                
                $alignCheck = array("horizontal" => "", "vertical" => "");
                $positionCheck = array("left" => "", "center" => "", "right" => "");
                $alignR = Mage::getModel('soshake/soshake')->getCollection();
                $alignR->addFilter('hook',array('eq' => "align"));
                foreach($alignR as $result) { $alignCheck[$result->getData("feature")] = "checked"; }
                $positionR = Mage::getModel('soshake/soshake')->getCollection();
                $positionR->addFilter('hook',array('eq' => "position"));
                foreach($positionR as $result) { $positionCheck[$result->getData("feature")] = "checked"; }
                $textIntro = Mage::getModel('soshake/soshake')->getCollection();
                $textIntro->addFilter('hook',array('eq' => "up2-text-intro"));
                foreach($textIntro as $result) { $intro = $result->getData("feature"); }
                
                $content .= "
                <div class='up2-content' id='soshakelayout'>
                        <h1>Choisir l'apparence des Soshake</h1>
                        <p>Pour choisir le type des boutons de partage (Facebook, Twitter, Google+), vous devez vous rendre sur votre compte sur <b>Up 2 Social</b><br /><a href=\"http://up2social.com/front\" target=\"_blank\" class=\"up2-button\">Accéder à mon compte sur Up 2 Social</a></p>
                        <form method=\"post\" action=\"".$base_url."adminsoshake/adminhtml_index\" id=\"up2-form-hook\">
                                <input name=\"form_key\" type=\"hidden\" value=\"".Mage::getSingleton('core/session')->getFormKey()."\"  />
                                <h2>Alignement de vos SoShake</h2>
                                <p>
                                        <input type=\"radio\" name=\"up2-align\" value=\"vertical\" ".$alignCheck["vertical"]." /> Vertical (les boutons de partages seront les uns au dessus des autres)<br />
                                        <input type=\"radio\" name=\"up2-align\" value=\"horizontal\" ".$alignCheck["horizontal"]." /> Horizontal (les boutons de partages seront les uns à côté des autres)<br />
                                </p>
                                <h2>Disposition de vos SoShake</h2>
                                <p>
                                        <input type=\"radio\" name=\"up2-position\" value=\"left\" ".$positionCheck["left"]." /> Gauche (les boutons de partages seront placés sur la gauche)<br />
                                        <input type=\"radio\" name=\"up2-position\" value=\"center\" ".$positionCheck["center"]." /> Centre (les boutons de partages seront placés au centre)<br />
                                        <input type=\"radio\" name=\"up2-position\" value=\"right\" ".$positionCheck["right"]." /> Droite (les boutons de partages seront placés à droite)<br />
                                </p>
                                <input type=\"submit\" name=\"submit\" class=\"submit\" value=\"Enregistrer cette disposition\" />
                        </form>
                        <form method=\"post\" action=\"".$base_url."adminsoshake/adminhtml_index\" id=\"up2-form-hook\">
                                <input name=\"form_key\" type=\"hidden\" value=\"".Mage::getSingleton('core/session')->getFormKey()."\"  />
                                <h2>Texte introduisant les produits liés</h2>
                                <input type=\"text\" name=\"up2-text-intro\" value=\"".$intro."\" style=\"padding:3px;font-size:12pt;width:400px;\" /> <br />
                                <input type=\"submit\" name=\"submit\" class=\"submit\" value=\"Enregistrer ce texte\" />
                        </form>
                </div>
                ";
                if($update->layout || $update->text) $content .= '<script type="text/javascript">jQuery(\'#soshakelayout\').show();</script>';

                $content .= "
                <div class='up2-content' id='copycode'>
                        <h1>Insertion manuelle des SoShake</h1>
                        <p>Vous pouvez insérer les SoShakes n'importe où dans vos pages, vous même. Soit en utilisant l'installeur ci dessus, ou en insérant vous même nos balises à l'endroit souhaité dans vos pages</p>
                        <p>Pour insérer le bouton <b>\"Like\" de Facebook</b>, copiez le code suivant à l'endroit où vous souhaitez l'afficher : <br />
                        <input type='text' value='".htmlentities("<div class=\"up2-like\"></div>")."' onClick='select();' /></p>
                        <p>Pour insérer le bouton <b>\"Tweet\" de Twitter</b>, copiez le code suivant à l'endroit où vous souhaitez l'afficher : <br />
                        <input type='text' value='".htmlentities("<div class=\"up2-tweet\"></div>")."' onClick='select();' /></p>
                        <p>Pour insérer le bouton <b>\"+1\" de Google</b>, copiez le code suivant à l'endroit où vous souhaitez l'afficher : <br />
                        <input type='text' value='".htmlentities("<div class=\"up2-gplus\"></div>")."' onClick='select();' /></p>
                        <p>Pour insérer le bouton <b>\"Share\" de LinkedIn</b>, copiez le code suivant à l'endroit où vous souhaitez l'afficher : <br />
                        <input type='text' value='".htmlentities("<div class=\"up2-linkedin\"></div>")."' onClick='select();' /></p>
                        <p>&nbsp;</p>
                        <p>
                        Pour insérer une <b>FanBox d\'une page Facebook</b>, copiez le code suivant à l'endroit où vous souhaitez l'afficher : <br />
                        <input type='text' value='".htmlentities("<div class=\"up2-fanbox\"></div>")."' onClick='select();' /><br /><br />
                        La page Facebook affichée est celle que vous avez définie dans votre compte Up 2 Social.<br />
                        Pour afficher la Fanbox il vous suffit de modifier ce code de la façon suivante : <br />
                        <code>".htmlentities("<div class=\"up2-fanbox\" url=\"ADRESSE_DE_VOTRE_PAGE_FACEBOOK\"></div>")."</code>
                        </p>
                        <p>&nbsp;</p>
                        <p>Pour insérer le <b>Facebook Connect</b> et permettre à vos visiteurs de se créer un compte d'un clic avec leur compte Facebook, copiez le code suivant à l'endroit où vous souhaitez l'afficher : <br />
                        <input type='text' value='".htmlentities("<div class=\"up2-fbconnect\" url=\"".Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)."soshake\"></div>")."' onClick='select();' /></p>
                </div>
                ";
                
                $content .= "
                <div class='up2-content' id='actions'>
                        <p>
                                Pour gérer les actions affichées suite à un partage vous devez vous rendre dans la categorie \"Actions\" sur le site Up 2 Social : <br />
                                <br />
                                <a href=\"http://up2social.com/front\" target=\"_blank\" class=\"up2-button\">Accéder à mon compte sur Up 2 Social</a>
                        </p>
                </div>
                ";
                
                $this->loadLayout();
                $block = $this->getLayout()
                ->createBlock('core/text', 'up2-soshake-block')
                ->setText($content);
                
                $this->_addContent($block);
                
                $this->renderLayout();
        }
        
        public function hasSoShake($hook,$where) {
                $collection = Mage::getModel('soshake/soshake')->getCollection();
                $collection->addFilter('hook',array('eq' => $hook));
                $collection->addFilter('BorA',array('eq' => $where));
                
                if(count($collection) == 0) return FALSE;
                else {
                        foreach($collection as $data) {
                                $return[$data->getData('feature')] = 1;
                        }
                        return $return;
                }
        }
        
        public function displayClass($hook,$where) {
                if($this->hasSoShake($hook,$where)) return " up2-hasSoShake";
                else return "";
        }
        
        public function jsArray($hook,$where) {
                if($features = $this->hasSoShake($hook,$where)) {
                        if($features["like"] == "") $features["like"] = 0;
                        if($features["tweet"] == "") $features["tweet"] = 0;
                        if($features["gplus"] == "") $features["gplus"] = 0;
                        if($features["fanbox"] == "") $features["fanbox"] = 0;
                        if($features["fbconnect"] == "") $features["fbconnect"] = 0;
                        return "new Array(".$features["like"].", ".$features["tweet"].", ".$features["gplus"].", ".$features["fanbox"].", ".$features["fbconnect"].")";
                }
                else return "new Array(0,0,0,0,0)";
        }
}