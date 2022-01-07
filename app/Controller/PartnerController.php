<?php

class PartnerController extends Controller{
 
    public function index(){
        $this->loadModel('Partenaire');
        $d['pages']= $this->Partenaire->find(array());//select *
        $this->set($d);
    }

    public function view($id=null){
        $this->loadModel('Partenaire');
        $d['page'] = $this->Partenaire->findFirst(array(
            'condition' => array('id' => $id )
        ));
        if(empty($d['page'])){//empty($test)
            $this->e404('Page introuvable');
        }else{
            
        }
        //print_r($test);
        //$this->set('test',$test);
        $d['pages']= $this->Partenaire->find(array());//select *
        $this->set($d);
        
    }
    public function getAll(){
        $this->loadModel('Partenaire');
        return $this->Partenaire->find(array());
    }

    
    /**
     * Fonction Backoffice
     */
    function admin_list(){
        $nb = 1 ;
        $this->loadModel('Partenaire');
        //print_r($test);
        //$this->set('test',$test);
        $d['pages']= $this->Partenaire->find(array(
            'fields' => 'id,site ',
            //'limit' => $nb*($this->request->page),
            
        ));//select 
        $d['total'] = $this->Partenaire->findCount();
        $d['page'] = ceil($d['total']/$nb);
        $this->set($d);
        //debug($d);die();
    }

    function admin_delete($id){
        $this->loadModel('Partenaire');
        $this->Partenaire->delete($id);
        $this->Session->setFlash(' le contenu a bien été supprimé','SUCCES');
        $this->redirect('admin/partner/list');
    }

   

    public function admin_edit($id=null){
        $this->loadModel('Partenaire');
        $d['id'] = '';
        if($this->request->data){
            //if($this->Bien->validates($this->request->data)){
            $this->Partenaire->save($this->request->data);
            $this->Session->setFlash(' le contenu a bien été sauvegardé','SUCCES');
            $id = $this->Partenaire->id;
            //}else { $this->Session->setFlash(' Veuillez bien remplir tt les champs ','ECHEC');}
            
        }else{
            if($id){
                $this->request->data = $this->Partenaire->findFirst(array(
                    'condition' => array('id' => $id)
                ));
                $d['id'] = $id;
            }
        }
        $this->set($d);
    }
}
