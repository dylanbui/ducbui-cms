<?php
class Digitalus_View_Helper_Cms_FckEditor
{

    /**
     * i know it is not well liked to output this here, but for integration purposes it makes sense
     */
    public function FckEditor($instance = 'content', $value = 'Enter text here', $height = 600, $width = 600, $fullToolbar = true)
    {
        $view=$this->view;
        include('Digitalus/editor/fckeditor.php') ;
        ?>
        <script>
        function FCKeditor_OnComplete( editorInstance )
        {
        }
        </script>

        <?php
        $sBasePath = '/scripts/fckeditor/' ;

        $oFCKeditor = new FCKeditor($instance) ;
        $oFCKeditor->BasePath = $sBasePath ;
        $oFCKeditor->Config['SkinPath'] = $sBasePath . 'editor/skins/office2003/' ;
        $oFCKeditor->Width      = $width ;
        $oFCKeditor->Height     = $height ;
        if ($fullToolbar) {
            $oFCKeditor->ToolbarSet = 'Digitalus' ;
        } else {
            $oFCKeditor->ToolbarSet = 'Basic' ;
        }
        $oFCKeditor->Value = $value ;

        $oFCKeditor->Create() ;

    }

    /**
     * Set this->view object
     *
     * @param  Zend_View_Interface $view
     * @return Zend_View_Helper_DeclareVars
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }

}