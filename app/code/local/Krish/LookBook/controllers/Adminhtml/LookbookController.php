<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Adminhtml_LookbookController extends Mage_Adminhtml_Controller_action 
{

    /**
     * Init layout, menu and breadcrumb
     *
     * @return Mage_Adminhtml_LookBook_LookbookController
     */
    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('lookbook/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Lookbook Manager'), Mage::helper('adminhtml')->__('Lookbook Manager'));

        return $this;
    }

    /**
     * LookBooks grid
     */
    public function indexAction() {
        $this->_initAction()
             ->renderLayout();
    }

    /**
     * Edit LookBooks page
     */
    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('lookbook/lookbook')->load($id);
        Mage::register('current_lookbook', $model);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('lookbook_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('lookbook/items');
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Lookbook Manager'), Mage::helper('adminhtml')->__('Lookbook Manager'));
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('lookbook/adminhtml_lookbook_edit'))
                    ->_addLeft($this->getLayout()->createBlock('lookbook/adminhtml_lookbook_edit_tabs'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('lookbook')->__('Lookbook does not exist'));
            $this->_redirect('*/*/');
        }
    }

    /**
     * Create new LookBook 
     */
    public function newAction() {
        $this->_forward('edit');
    }

    /**
     * Save action
     */
    public function saveAction() 
    {
        if ($data = $this->getRequest()->getPost()) {
          if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
               try {
                       $baseDir = Mage::getBaseDir('media').DS.'lookbook';
                        $file = new Varien_Io_File();
                            if(!is_dir($baseDir)){
                                mkdir($baseDir,0777,true);  
                            }
                        $fileName       = $_FILES['image']['name'];
                        $tmp = explode(".",$fileName);
                        $fileName = time().uniqid().'.'.end($tmp);
                        $image_to_upload = "";
                        $uploader = new Varien_File_Uploader('image');
                        $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
                        $uploader->setAllowRenameFiles(true);
                        $uploader->setFilesDispersion(false);
                        $path = Mage::getBaseDir('media') . DS.'lookbook'.DS ;
                        $sitePath = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'lookbook';
                        $uploader->save($path, $fileName);
                        $newFilename = $uploader->getUploadedFileName();
                        $data['image'] = $sitePath.'/'.$newFilename;
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError( $e->getMessage());
                    $this->_redirect('*/*/');  
                    return;
                }
            }
             else {   
                    if(isset($data['image']['delete']) && $data['image']['delete'] == 1) {
                        $data['image'] = '';
                        $path = Mage::getBaseDir('media') . DS . 'lookbook';
                        $model = Mage::getModel('lookbook/lookbook');
                        $loadlookbook = $model->load($this->getRequest()->getParam('id'));
                        $imageName = $loadlookbook->getData('image');
                        $resizeEndarray = explode("/",$imageName);
                        unlink($path.'/'.end($resizeEndarray)); //delete it
                    } else {
                        unset($data['image']);
                    }
                }
                $model = Mage::getModel('lookbook/lookbook');
                $model->setData($data)
                      ->setId($this->getRequest()->getParam('id'));

            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('lookbook')->__('Lookbook was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('lookbook')->__('Unable to find Lookbook to save'));
        $this->_redirect('*/*/');
    }

    /**
     * Delete action
     */
    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('lookbook/lookbook');

                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Lookbook was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * Delete specified LookBooks using grid massaction
     *
     */
    public function massDeleteAction() {
        $lookbookIds = $this->getRequest()->getParam('lookbook');
        if (!is_array($lookbookIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Lookbook(s)'));
        } else {
            try {
                foreach ($lookbookIds as $lookbookId) {
                    $lookbook = Mage::getModel('lookbook/lookbook')->load($lookbookId);
                    $lookbook->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($lookbookIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * Change status for selected LookBooks
     */
    public function massStatusAction() {
        $lookbookIds = $this->getRequest()->getParam('lookbook');
        if (!is_array($lookbookIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select Lookbook(s)'));
        } else {
            try {
                foreach ($lookbookIds as $lookbookId) {
                    $lookbook = Mage::getSingleton('lookbook/lookbook')
                            ->load($lookbookId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($lookbookIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * Export lookbook grid to CSV format
     */
    public function exportCsvAction() {
        $fileName = 'lookbook.csv';
        $content = $this->getLayout()->createBlock('lookbook/adminhtml_lookbook_grid')
                ->getCsv();
        $this->_sendUploadResponse($fileName, $content);
    }

    /**
     *  Export lookbook grid to Excel XML format
     */
    public function exportXmlAction() {
        $fileName = 'lookbook.xml';
        $content = $this->getLayout()->createBlock('lookbook/adminhtml_lookbook_grid')
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
     * Products chooser Action (Ajax request)
     *
     */
    public function productsAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock('products.grid')
                ->setProdlist($this->getRequest()->getPost('products_prodlist', null));
        $this->renderLayout();
    }
    
    /**
     * Products chooser Action (Ajax request)
     *
     */
    public function productsgridAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock('products.grid')
                ->setProdlist($this->getRequest()->getPost('products_prodlist', null));
        $this->renderLayout();
    }

   

}