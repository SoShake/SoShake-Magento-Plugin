<?
class Up2social_Soshake_Model_Observer extends Varien_Event_Observer
{
        const MODULE_NAME = 'Up2social_Soshake';
     
        public function __construct()
        {
                $hooks = array(
                        array("product.info",                   "Haut la fiche produit",        "Bas la fiche produit"),
                        array("product.info.media",             "Haut photo produit",           "Sous photo produit"),
                        array("product.info.simple",            "sous nom produit",             "Sous le prix"),
                        array("product.info.addtocart",         "au dessus 'ajouter panier'",   "Apres bouton acheter, avant 'ajouter à autre chose'"),
                        array("product.info.addto",             "au dessus 'ajouter à'",        "Apres 'ajouter à autre chose'"),
                        array("product.description",            "Avant description",            "Apres description"),
                        array("product.attributes",             "Avant details tech",           "Apres details tech"),
                        array("product.info.upsell",            "Avant reco",                   "Apres reco"),
                        array("product.product_tag_list",       "Avant tag",                    "Apres tags"),
                        array("catalog.product.related",        "Avant produit lié colonne",    "Apres produits lié colonne"),
                        array("catalog.compare.sidebar",        "Avant comp. produit sidebar",  "Apres comparaison produit sidebar"),
                        array("cart_sidebar",                   "avant shopping cart",          "apres shopping cart"),
                        array("cms.wrapper",                    "avant le contenu d'une page",  "apres le contenu page"),
                        array("footer",                         "avant footer",                 "apres footer"),
                );
        }
        
        //Pensez à ajouter les meta OG sur l'event "head"
        
        public function insertUp2SocialSoShake($observer = NULL)
        {
                //Pour éviter la création de fichier template, pouvant être écrasé, nous générons les widget qui seront inséré à la volée selon les lieu défini par le site
                if (!$observer) {
                        return;
                }
                
                $transport = $observer->getEvent()->getTransport();
                $hookName = $observer->getEvent()->getBlock()->getNameInLayout();
                
                $collection = Mage::getModel('soshake/soshake')->getCollection();
                $collection->addFilter('hook',array('eq' => $hookName));
                
                $product = Mage::registry('current_product');
                if($product) { $catgorie = $product->getCategory(); }
                else { $catgorie = ""; }
                
                if ($collection->count()>0) {
                        $currentCategory = Mage::registry('current_category');
                        $insert["like"]           = '<div class="up2-like" categorie="'.$catgorie.'" ></div>';
                        $insert["tweet"]          = '<div class="up2-tweet" categorie="'.$catgorie.'" ></div>';
                        $insert["gplus"]          = '<div class="up2-gplus" categorie="'.$catgorie.'" ></div>';
                        $insert["fanbox"]         = '<div class="up2-fanbox"></div>';
                        $insert["fbconnect"]      = '<div class="up2-fbconnect" url="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'soshake?goto="></div>';
                        
                        if (!Mage::getStoreConfig('advanced/modules_disable_output/'.self::MODULE_NAME)) {
                                $alignR = Mage::getModel('soshake/soshake')->getCollection();
                                $alignR->addFilter('hook',array('eq' => "align"));
                                foreach($alignR as $result) { $align = $result->getData("feature"); }
                                $positionR = Mage::getModel('soshake/soshake')->getCollection();
                                $positionR->addFilter('hook',array('eq' => "position"));
                                foreach($positionR as $result) { $position = $result->getData("feature"); }
                                if($position == "") $position = "center";
                                if($align == "") $align = "horizontal";
                                
                                $block = new Up2social_Soshake_Block_InsertUp2SocialSoShake();
                                $block->setPassingTransport($transport['html'], $observer->getEvent()->getBlock()->getNameInLayout());
                                $content = $transport['html'];
                                $before = ""; $after = "";
                                foreach($collection as $widget) {
                                        if($widget->getData("BorA") == "before" && $align == "vertical") $before = "<tr><td style='text-align:$position;'>".$insert[$widget->getData("feature")]."</td></tr>".$before;
                                        elseif($widget->getData("BorA") == "before" && $align == "horizontal") $before = "<td style='text-align:$position;'>".$insert[$widget->getData("feature")]."</td>".$before;
                                        elseif($align == "vertical") $after = $after."<tr><td style='text-align:$position;'>".$insert[$widget->getData("feature")]."</td></tr>";
                                        else $after = $after."<td style='text-align:$position;'>".$insert[$widget->getData("feature")]."</td>";
                                }
                                if($before != "") $before = "<table style='width:100%'><tr>".$before."</tr></table>";
                                if($after != "") $after = "<table style='width:100%'><tr>".$after."</tr></table>";
                                $block->setData('text', $before.$content.$after);
                                $block->toHtml();
                        }
                }
                
                if($hookName == "footer") {
                        if($product) {
                                $textIntro = Mage::getModel('soshake/soshake')->getCollection();
                                $textIntro->addFilter('hook',array('eq' => "up2-text-intro"));
                                foreach($textIntro as $result) { $intro = $result->getData("feature"); }
                                $up2_relatedProducts = $product->getCrossSellProductIds();
                                $crossSellProductJS = '
                                        <div id="u2s-CrossSellProduct-single" style="display:none;">
                                                <div class="up2-text">'.$intro.'</div>
                                                <ul class="up2-carrousel-products">
                                                ';
                                                        $up2I = 1;
                                foreach($up2_relatedProducts as $up2_relatedProduct) {
                                        $crossSellProductJS .= '
                                                <li id="up2-product'.$up2I.'" style="display:none;">
                                                <table>
                                                        <tr>
                                                                <td>
                                                                        <a href="'.Mage::getModel('catalog/product')->load($up2_relatedProduct)->getProductUrl().'" class="u2s-CrossSellProduct-lien">
                                                                        <img src="'.Mage::getModel('catalog/product')->load($up2_relatedProduct)->getImageUrl().'" />
                                                                        </a>
                                                                </td>
                                                                <td>
                                                                        <a href="'.Mage::getModel('catalog/product')->load($up2_relatedProduct)->getProductUrl().'" class="u2s-CrossSellProduct-lien">
                                                                        <div class="up2-h2">'.Mage::getModel('catalog/product')->load($up2_relatedProduct)->getName().'</div>
                                                                        <p class="up2-price">';
                                                                        
                                                                        if(Mage::getModel('catalog/product')->load($up2_relatedProduct)->getFinalPrice() != Mage::getModel('catalog/product')->load($up2_relatedProduct)->getPrice()) $crossSellProductJS .= "<s>".round(Mage::getModel('catalog/product')->load($up2_relatedProduct)->getPrice(),2)."</s> ";
                                                                        
                                                                        $crossSellProductJS .= Mage::getModel('catalog/product')->load($up2_relatedProduct)->getFormatedPrice().'</div>
                                                                        <p>'.Mage::getModel('catalog/product')->load($up2_relatedProduct)->getShortDescription().'</p>
                                                                        </a>
                                                                        <a href="'.Mage::getUrl('checkout/cart/add', array('product' => $up2_relatedProduct)).'" class="u2s-CrossSellProduct-lien u2s-add-to-cart">Ajouter au panier</a>
                                                                </td>
                                                       </tr>
                                                </table>
                                                </li>
                                                                ';
                                                                $up2I++;
                                }
                                $crossSellProductJS .= '
                                        <img src="http://up2social.com/lib/images/arrow_left.png" id="up2-arrow-left" />
                                        <img src="http://up2social.com/lib/images/arrow_right.png" id="up2-arrow-right" />
                                        </div>
                                        <div id="u2s-CrossSellProduct-grid" style="display:none;">
                                                <div class="up2-text">'.$intro.'</div>
                                                <table>
                                                        <tr>';
                                                        $up2I = 1;
                                foreach($up2_relatedProducts as $up2_relatedProduct) {
                                        $crossSellProductJS .= '
                                                                <td>
                                                                        <a href="'.Mage::getModel('catalog/product')->load($up2_relatedProduct)->getProductUrl().'" class="u2s-CrossSellProduct-lien">
                                                                                <img src="'.Mage::getModel('catalog/product')->load($up2_relatedProduct)->getImageUrl().'" />
                                                                                <div class="up2-h2">'.Mage::getModel('catalog/product')->load($up2_relatedProduct)->getName().'</div>
                                                                                <p class="up2-price">'.Mage::getModel('catalog/product')->load($up2_relatedProduct)->getFormatedPrice().'</div>
                                                                        </a>
                                                                </td>
                                                                ';
                                                                $up2I++;
                                                                if($up2I>3) break;
                                }
                                $crossSellProductJS .= '
                                                       </tr>
                                                </table>
                                        </div>';
                        } else $crossSellProductJS = "";
                        $block = new Up2social_Soshake_Block_InsertUp2SocialSoShake();
                        $block->setPassingTransport($transport['html'], $observer->getEvent()->getBlock()->getNameInLayout());
                        $block->setData('text', $transport['html'].'
                                <script type="text/javascript" src="http://up2social.com/api/FanBox.js"></script>
                                <script type="text/javascript" src="http://up2social.com/api/FBConnect.js"></script>
                                <script type="text/javascript" src="http://up2social.com/api/LikeButton.js"></script>
                                '.$crossSellProductJS.'
                                ');
                        $block->toHtml();
                }
                if($hookName == "head") {
                        $headBlock = $observer->getEvent ()-> getBlock ( 'head' ); 
                        if ( $headBlock ) { 
                                $pageTitle = $headBlock->getTitle (); 
                        } 
                        $client = json_decode(file_get_contents("http://up2social.com/api/me.json?url=".Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)));
                        $block = new Up2social_Soshake_Block_InsertUp2SocialSoShake();
                        $block->setPassingTransport($transport['html'], $observer->getEvent()->getBlock()->getNameInLayout());
                        if($product) { $description = $product->getShortDescription(); $image = $product->getMediaConfig()->getMediaUrl(Mage::registry('current_product')->getData('image')); }
                        else { $description = ""; $image = ""; }
                        
                        $block->setData('text', $transport['html'].'
                                                <meta property="og:title" content="'.$pageTitle.'"/>
                                                <meta property="og:type" content="website"/>
                                                <meta property="og:url" content="http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"].'"/>
                                                <meta property="og:image" content="'.$image.'"/>
                                                <meta property="og:site_name" content="'.strip_tags(Mage::app()->getStore()->getName()).'"/>
                                                <meta property="fb:admins" content="'.$client->result->facebookAdminID.'"/>
                                                <meta property="og:description" content="'.strip_tags($description).'"/>
                                                
                                                ');
                        $block->toHtml();
                }
                
                return $this;
        }
}
?>