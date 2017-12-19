<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Adminhtml_InfluencerController extends Mage_Adminhtml_Controller_action 
{

    /**
     * Init layout, menu and breadcrumb
     *
     * @return Krish_LookBook_Adminhtml_InfluencerController
     */
    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('lookbook/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Influencer Manager'), Mage::helper('adminhtml')->__('Influencer Manager'));

        return $this;
    }

    /**
     * Influencer grid
     */
    public function indexAction() {
     
        $this->_initAction()
             ->renderLayout();
    }

    /**
     * Edit Influencer page
     */
    public function editAction() 
    {

        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('lookbook/influencer')->load($id);
        Mage::register('current_influencer', $model);
        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('influencer_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('lookbook/items');
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Influencer Manager'), 
                                  Mage::helper('adminhtml')->__('Influencer Manager'));
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('lookbook/adminhtml_influencer_edit'))
                    ->_addLeft($this->getLayout()->createBlock('lookbook/adminhtml_influencer_edit_tabs'));
            $this->renderLayout();

        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('lookbook')->__('Influencer does not exist'));
            $this->_redirect('*/*/');
        }
    }

    /**
     * Create new Influencer 
     */
    public function newAction() {
        $this->_forward('edit');
    }

    /**
     * Save Influencer 
     */
    public function saveAction() 
    {
        if ($data = $this->getRequest()->getPost()) {
          if (isset($_FILES['influencer_image']['name']) && $_FILES['influencer_image']['name'] != '') {
                try {
                        $baseDir = Mage::getBaseDir('media').DS.'lookbook'.DS.'influencer';
                        $file = new Varien_Io_File();
                        if(!is_dir($baseDir)){
                            mkdir($baseDir,0777,true);  
                        }         
                        $fileName   = $_FILES['influencer_image']['name'];
                        $tmp        = explode(".",$fileName);
                        $fileName   = time().uniqid().'.'.end($tmp);
                        $image_to_upload = "";
                        $uploader   = new Varien_File_Uploader('influencer_image');
                        $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
                        $uploader->setAllowRenameFiles(false);
                        $uploader->setFilesDispersion(false);
                        $path = Mage::getBaseDir('media') . DS.'lookbook'.DS.'influencer'.DS ;
                        $sitePath   = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'lookbook'.DS.'influencer';
                        $uploader->save($path, $fileName);
                        $newFilename    = $uploader->getUploadedFileName();
                        $data['influencer_image'] = $sitePath.'/'.$newFilename;

                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError( $e->getMessage());
                    $this->_redirect('*/*/');  
                    return;
                }
                //this way the name is saved in DB
            }
             else {
                    if(isset($data['influencer_image']['delete']) && $data['influencer_image']['delete'] == 1) {
                        $data['influencer_image'] = '';
                        $path = Mage::getBaseDir('media') . DS . 'lookbook'.DS.'influencer';
                        $model = Mage::getModel('lookbook/influencer');
                        $loadlookbook = $model->load($this->getRequest()->getParam('id'));
                        $imageName = $loadlookbook->getData('influencer_image');
                        $resizeEndarray = explode("/",$imageName);
                          unlink($path.'/'.end($resizeEndarray)); //delete it
                    } else {
                        unset($data['influencer_image']);
                       
                    }
                }
             $lookbooks = array();
             $availLookbookIds = Mage::getResourceModel('lookbook/lookbook_collection')
                                    ->getAllIds();
             parse_str($data['influencer_lookbook'], $lookbooks);
                foreach ($lookbooks as $k => $v) {
                    if (preg_match('/[^0-9]+/', $k) || preg_match('/[^0-9]+/', $v)) {
                        unset($lookbooks[$k]);
                    }
                }
            $lookbookIds = array_intersect($availLookbookIds, $lookbooks);
            $data['lookbook_ids'] = implode(',', $lookbookIds);
            $model = Mage::getModel('lookbook/influencer');
            $model->setData($data)
                  ->setId($this->getRequest()->getParam('id'));
            try {
                if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())
                            ->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('lookbook')->__('Influencer was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('lookbook')->__('Unable to find influencer to save'));
        $this->_redirect('*/*/');
    }

    /**
     * Delete action
     */
    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('lookbook/influencer');

                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Influencer was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * Delete specified Influencers using grid massaction
     *
     */
    public function massDeleteAction() {
        $influencerIds = $this->getRequest()->getParam('influencer');
        if (!is_array($influencerIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select influencer(s)'));
        } else {
            try {
                foreach ($influencerIds as $influencerId) {
                    $lookbook = Mage::getModel('lookbook/influencer')->load($influencerId);
                    $lookbook->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($influencerIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * Change status for selected Influencers
     */
    public function massStatusAction() 
    {
        $influencerIds = $this->getRequest()->getParam('influencer');
        if (!is_array($influencerIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select influencer(s)'));
        } else {
            try {
                foreach ($influencerIds as $influencerId) {
                    $lookbook = Mage::getSingleton('lookbook/influencer')
                            ->load($influencerId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($influencerIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * Export Influencers grid to CSV format
     */
    public function exportCsvAction() {
        $fileName = 'influencer.csv';
        $content = $this->getLayout()->createBlock('lookbook/adminhtml_influencer_grid')
                ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    /**
     * Export Influencers grid to XML format
     */
    public function exportXmlAction() {
        $fileName = 'influencer.xml';
        $content = $this->getLayout()->createBlock('lookbook/adminhtml_influencer_grid')
                ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    /**
     * Prepare file download response
     *     
     */
    protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream') {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }

    /**
     * Lookbooks chooser Action (Ajax request)
     *
     */
    public function lookbookgridAction() {
        $this->_initAction();
        $this->getResponse()->setBody(
               $this->getLayout()->createBlock('lookbook/adminhtml_influencer_edit_tab_lookbook')->toHtml()
        );
    }

    
   

}