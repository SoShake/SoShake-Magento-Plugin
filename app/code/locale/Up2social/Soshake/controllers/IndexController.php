<?php

class Up2social_Soshake_IndexController extends Mage_Core_Controller_Front_Action 
{
	public function indexAction()
	{
                if( $this->getRequest()->getPost() != array() && $this->getRequest()->getPost('up2FBConnect') == 1) {
                        
                        $collection_fbc = Mage::getModel('customer/customer')->getCollection()->addFieldToFilter('email', $this->getRequest()->getPost('email'));
                        $customer       = Mage::getModel('customer/customer');
                        $passwd         = md5($this->getRequest()->getPost('email'));
                        $websiteID      = Mage::app()->getStore()->getStoreId();
                        
                        if ($collection_fbc->count() == 0) {
                                $customer ->setFirstname($this->getRequest()->getPost('first_name'))
                                    ->setLastname($this->getRequest()->getPost('last_name'))
                                    ->setEmail($this->getRequest()->getPost('email'))
                                    ->setPassword($passwd)
                                    ->setWebsiteId($websiteID)
                                    ->setConfirmation($passwd);
                                $customer->save();
                        } else {
                                
                        }
                        
                        $customer->setWebsiteId($websiteID)->loadByEmail($this->getRequest()->getPost('email'));
                        $session = Mage::getSingleton('customer/session');
                        $session->login($this->getRequest()->getPost('email'), $passwd);
                        
                        if ($session->isLoggedIn()) {
                                if ($referer = $this->getRequest()->getParam(Mage_Customer_Helper_Data::REFERER_QUERY_PARAM_NAME)) {
                                        $referer = Mage::helper('core')->urlDecode($referer);
                                        if ($this->_isUrlInternal($referer)) {
                                                $session->setBeforeAuthUrl($referer);
                                        } 
                                        
                                        if ($this->_isUrlInternal($referer)) {
                                                $session->setBeforeAuthUrl($referer);
                                        } else {
                                                $session->setBeforeAuthUrl(Mage::getUrl($this->getRequest()->getParam('referer')));
                                        }
                                } else {
                                        $session->setBeforeAuthUrl(Mage::helper('customer')->getLoginUrl());
                                }
                                $this->_redirectUrl($session->getBeforeAuthUrl(true)); 
                                return; 
                        }
                }
	}

	public function testAction()
	{
		echo 'Inside Up2social_Soshake_IndexController::testAction()'; exit;
	}
}