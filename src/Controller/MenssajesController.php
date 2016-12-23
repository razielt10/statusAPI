<?php
namespace App\Controller;

use App\Controller\AppController;
//use Cake\Utility\Security;
//use EmailQueue\EmailQueue;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
Use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\Mailer\Email;
use Cake\ORM\Query;

use Cake\Validation\Validator;
Use Cake\Core\App;
/**
 * Menssajes Controller
 *
 * @property \App\Model\Table\MenssajesTable $Menssajes
 */
class MenssajesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function status()
    {
        //$status['status']=1; 
        //echo 'validation->'.($this->request->query('r'));
        //$status['code']=0;
        $err = [];
        $limit = 20;
        $page = 1;
        $search = '%%';
        if ($this->request->is('get')) {

            $errors = $this->Menssajes->newEntity( $this->request->query, ['validate' => 'QueryParamaters'] );

            if($errors->errors()){
                $messages =  [ 400002 => 'The limit must be a integer parameter',
                               400003 => 'The page must be a integer parameter',
                               400004 => 'The query must be a alpha-numeric parameter'];
                $a=0;
                foreach( $errors->errors() as $list){
                    if(is_array($list)){
                        foreach($list as $error){ $a++;
                            $err[$a]['code']    =   $error;
                            $err[$a]['message']    =   $messages[$error];
                        }
                    }else{
                        $err[]    =   $list;
                    }
                }
            }else{
                $limit=$this->request->query('r');
                $page=$this->request->query('p');
                $search='%'.$this->request->query('q').'%';
            }

        }else{
            $limit = 20;
            $page = 1;
            $search = '%%';
        }
        if(!$err){
            try {

                if($limit<=0 || $limit=='' || strlen($limit)==0){ $limit = 20; }
                if($page<=0 || $page=='' || strlen($page)==0){ $page = 1; }

                //echo '$search->'.$search.',$limit->'.$limit.',$page->'.$page;
                $this->paginate['maxLimit'] = 20;
                $this->paginate['order'] = array('id' => 'desc');
                $this->paginate['fields'] = ['id', 'email', 'created_at', 'status'];
                //$this->paginate['conditions'] = array( 'not' => array ('created_at' => ''), 'status like' => $search);
                //$this->paginate['conditions'] = array( 'not' => array ('created_at' => ''), 'status like' => $search);
                $this->paginate['conditions'] = array( 'status like' => $search, 'activated' => '1');
                $this->paginate['limit'] = $limit;
                $this->paginate['page'] = $page;

                //$this->set('status', $this->paginate());
                $status = $this->paginate();
                //$this->set(compact('status'));

                $number = $status->count();
                if($number==0){
                    $err['code']=400000;            
                    $err['message']='status message not found (4)';
                    $err['link']=Configure::read('Error.message');
                    $this->set(compact('err'));
                    $this->set('_serialize', ['err']);
                }else{
                    $this->set('status', $status);
                    $this->set('_serialize', ['status']);
                }
                //$this->set(compact('status'));
               // $this->set('_serialize', ['status']);
            } catch (NotFoundException $e) {
                // Do something here like redirecting to first or last page.
                // $this->request->params['paging'] will give you required info.
                $err['code']=400001;            
                $err['message']='invalid number of rows';
                //$status['link']=$this->$linkMessage;
                $err['link']=Configure::read('Error.message');
                $this->set(compact('err'));
                $this->set('_serialize', ['err']);
            }
        }else{
        }

        

        //$status = $this->paginate($this->Menssajes, $conditions);

        
    }

    /**
     * View method
     *
     * @param string|null $id Menssaje id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {

        //print_r($this->request->params);
        if ($this->request->data('id')) {
            $errors = $this->Menssajes->newEntity( $this->request->data, ['validate' => 'IdStatusDelete']);

            if($errors->errors()){
                $messages =  [ 400002 => 'The status message must be a integer parameter'];
                $a=0;
                foreach( $errors->errors() as $list){
                    if(is_array($list)){
                        foreach($list as $error){ $a++;
                            $err[$a]['code']    =   $error;
                            $err[$a]['message']    =   $messages[$error];
                        }
                    }else{
                        $err[]    =   $list;
                    }
                } 
                    $err['link']=Configure::read('Error.message');
                    $this->set(compact('err'));
                    $this->set('_serialize', ['err']);

            }else{
                try{
                    $menssaje = $this->Menssajes->find('all', [ 'conditions' => ['id' => $this->request->data('id') , 'activated' => '1'] ])
                                                ->select(['id', 'email', 'created_at', 'status']);
                    //print_r($menssaje);
                    $number = $menssaje->count();
                    if($number==0){
                        $err['code']=400000;            
                        $err['message']='status message not found (4)';
                        $err['link']=Configure::read('Error.message');
                        $this->set(compact('err'));
                        $this->set('_serialize', ['err']);
                    }else{

                        foreach ($menssaje as $respuesta) {
                            $created_at=$respuesta->created_at;
                            $status=$respuesta->status;
                            $email1= $respuesta->email;
                            $id= $respuesta->id;
                        }

                        if($email1=='annonymus'){
                            $err['code']=400009;            
                            $err['message']='annon statuses cannot be deleted';
                            $err['link']=Configure::read('Error.message');
                            $this->set(compact('err'));
                            $this->set('_serialize', ['err']);
                        }else{

                            $menssaje->code = $code = md5(date('U'));

                            $menssajes = TableRegistry::get('menssajes');
                            $query = $menssajes->query();
                            $query->update()
                                ->set(['deleting' => 2, 'activated' => 1, 'code' => $code])
                                ->where(['id' => $id])
                                ->execute();

                            $menssaje2 = $this->Menssajes->find()
                                ->select(['email'])
                                ->where(['id' => $id]) ;

                            $email = new Email('default');
                            $email->viewVars(['code' => $code]);
                            $email->template('delete')
                                ->emailFormat('html')
                                ->from(['someone@domain.com' => 'SomeOne'])
                                ->to($email1)
                                ->subject('Notice! your confirmation delete link.')
                                ->send();

                            $this->set('status', $menssaje2);
                            $this->set('_serialize', ['status']);
                        }
                    }
                    
                } catch (NotFoundException $e) {
                    $err['code']=400000;            
                    $err['message']='status message not found (5)';
                    $err['link']=Configure::read('Error.message');
                    $this->set(compact('err'));
                    $this->set('_serialize', ['err']);

                }
            }
        }else if ($this->request->params) {
            $errors = $this->Menssajes->newEntity( $this->request->params['pass'], ['validate' => 'IdStatus']);

            if($errors->errors()){
                $messages =  [ 400002 => 'The status message must be a integer parameter'];
                $a=0;
                foreach( $errors->errors() as $list){
                    if(is_array($list)){
                        foreach($list as $error){ $a++;
                            $err[$a]['code']    =   $error;
                            $err[$a]['message']    =   $messages[$error];
                        }
                    }else{
                        $err[]    =   $list;
                    }
                } 
                    $err['link']=Configure::read('Error.message');
                    $this->set(compact('err'));
                    $this->set('_serialize', ['err']);

            }else{
                try{
                    $menssaje = $this->Menssajes->find('all', [ 'conditions' => ['id' => $id] ])
                                                ->select(['id', 'email', 'created_at', 'status']);
                    $number = $menssaje->count();
                    if($number==0){
                        $err['code']=400000;            
                        $err['message']='status message not found (1)';
                        $err['link']=Configure::read('Error.message');
                        $this->set(compact('err'));
                        $this->set('_serialize', ['err']);
                    }else{
                        $this->set('status', $menssaje);
                        $this->set('_serialize', ['status']);
                    }
                    
                } catch (NotFoundException $e) {
                    $err['code']=400000;            
                    $err['message']='status message not found (2)';
                    $err['link']=Configure::read('Error.message');
                    $this->set(compact('err'));
                    $this->set('_serialize', ['err']);

                }
            }
        }else{
            $err['code']=400000;            
            $err['message']='status message not found (3)';
            $err['link']=Configure::read('Error.message');
            $this->set(compact('err'));
            $this->set('_serialize', ['err']);

        }
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function post()
    {
        //print_r($this->request);
        $return = array();
        $menssaje = $this->Menssajes->newEntity();
        if ($this->request->is('post')) {
            $menssaje = $this->Menssajes->patchEntity($menssaje, $this->request->data);
            $menssaje->created_at = date('Y-m-d H:i:sO');
            $menssaje->code = $code = md5(date('U'));
            $menssaje->activated = 2;
           //print_r($menssaje->errors());
            //$menssaje = $this->Menssajes->newEntity( $this->request->query, ['validate' => 'QueryParamaters'] );
            if($menssaje->errors()){
                $err = [];
                $messages =  [ 400005 => 'The email format is invalid',
                               400006 => 'Status message must not exceed 120 characters',
                               400007 => 'Status message can not be empty'];
                $a=0;
                foreach( $menssaje->errors() as $list){
                    if(is_array($list)){
                        //print_r();
                        foreach($list as $error){ $a++;
                            $err[$a]['code']    =   $error;
                            $err[$a]['message']    =   $messages[$error];
                        }
                    }else{
                        $err[]    =   $list;
                    }
                    
                }
                $err['link']=Configure::read('Error.message');
                    $this->set(compact('err'));
                    $this->set('_serialize', ['err']);
            }else{
                if($menssaje->email==''){
                    $menssaje->email='annonymus';
                }
            }


            if ($result=$this->Menssajes->save($menssaje)) {
                $idStatus=$result->id;

                //$this->set(compact('idStatus'));
                //$this->set('_serialize', ['idStatus']);
                //print_r($menssaje);
                if($menssaje->email && $menssaje->email!='annonymus'){
                    $email = new Email('default');
                    $email->viewVars(['code' => $code]);
                    $email->template('default')
                        ->emailFormat('html')
                        ->from(['someone@domain.com' => 'SomeOne'])
                        ->to($menssaje->email)
                        ->subject('Thanks! your messaje has been registered.')
                        ->send();
                }
               // $this->Flash->success(__('The menssaje has been saved.'));
               // return $this->redirect(['action' => 'index']);
                /*$return['status']=1;            
                $return['msj']='The Status message has been saved';
                $this->set(compact('return'));
                $this->set('_serialize', ['return']);*/
            } else {
                //$this->Flash->error(__('The menssaje could not be saved. Please, try again.'));
                //$return['status']=0;            
                //$return['msj']='The Status message could not be saved Please, try again later.';
            }
        }

    }

    /**
     * Edit method
     *
     * @param string|null $id Menssaje id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function confirmation( $code = null)
    {
        //print_r($this->request);
        //die;
        //$return = array();
        //$menssaje = $this->Menssajes->get($id, [ 'contain' => [] ]);
        $menssaje = $this->Menssajes->newEntity();

        if($this->request->params['pass']){
            $this->request->data['code']=$code;
        }

        if ($this->request->is(['put']) || $this->request->params['pass']) {

            //$messajes = TableRegistry::get('messajes');

            $menssaje1 = $this->Menssajes->patchEntity( $menssaje, $this->request->data, ['validate' => 'Code']);
            //print_r($menssaje);
            if($menssaje1->errors()){
                $messages =  [ 400007 => 'The code must be a alpha-numeric'];
                $a=0;
                foreach( $menssaje1->errors() as $list){
                    if(is_array($list)){
                        foreach($list as $error){ $a++;
                            $err[$a]['code']    =   $error;
                            $err[$a]['message']    =   $messages[$error];
                        }
                    }else{
                        $err[]    =   $list;
                    }
                } 
                    $err['link']=Configure::read('Error.message');
                    $this->set(compact('err'));
                    $this->set('_serialize', ['err']);

            }else{
                //$menssaje = $this->Menssajes->patchEntity($menssaje, $this->request->data);
                $menssaje = $this->Menssajes->find()
                            ->select(['id', 'email', 'code', 'activated', 'deleting'])
                            ->where(['code' => $this->request->data('code')])
                            ;
                //$menssaje = $this->Menssajes->find('all', [ 'conditions' => ['code' => $this->request->data('code') ] ] );
                $number = $menssaje->count();
                //print_r($menssaje);

                if($number==0){
                    $err['code']=400000;            
                    $err['message']='Status message with this code not found';
                    $err['link']=Configure::read('Error.message');
                    $this->set(compact('err'));
                    $this->set('_serialize', ['err']);
                }else{

                    foreach ($menssaje as $respuesta) {
                        $activated=$respuesta->activated;
                        $deleting=$respuesta->deleting;
                        $email= $respuesta->email;
                        $id= $respuesta->id;
                    }
                    //echo '$activated->'.$activated,', $deleting->'.$deleting;
                    if($activated==2){
                        $menssajes = TableRegistry::get('menssajes');
                        $query = $menssajes->query();
                        $query->update()
                            ->set(['activated' => 1])
                            ->where(['id' => $id])
                            ->execute();

                        $menssaje2 = $this->Menssajes->find()
                            ->select(['email'])
                            ->where(['id' => $id]) ;

                        $this->set('status', $menssaje2);
                        $this->set('_serialize', ['status']);

                    }else if($deleting==2){
                        $menssajes = TableRegistry::get('menssajes');
                        $query = $menssajes->query();
                        $query->delete()
                            ->where(['id' => $id])
                            ->execute();

                        $status['message']='Status messaje has been deleted';
                        $this->set(compact('status'));
                        $this->set('_serialize', ['status']);

                    }else{
                        $err['code']=400008;            
                        $err['message']='This confirmation for status message has expired or be used';
                        $err['link']=Configure::read('Error.message');
                        $this->set(compact('err'));
                        $this->set('_serialize', ['err']);
                    }
                    

                    //$this->set('status', $menssaje);
                    //$this->set('_serialize', ['status']);
                }

            }

            

        /*if ($this->request->is(['put'])) {
            $menssaje = $this->Menssajes->patchEntity($menssaje, $this->request->data);
            if ($this->Menssajes->save($menssaje)) {
                //$this->Flash->success(__('The menssaje has been saved.'));
                //return $this->redirect(['action' => 'index']);
                $return['status']=1;
                $return['msj']='The Status message has been updated';

            } else {
                //$this->Flash->error(__('The menssaje could not be saved. Please, try again.'));
                $return['status']=0;
                $return['msj']='The Status message could not be update Please, try again later.';
            }
        }
        $this->set(compact('return'));
        $this->set('_serialize', ['return']);*/
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Menssaje id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $return = array();
        $this->request->allowMethod(['post', 'delete']);
        $menssaje = $this->Menssajes->get($id);
        if ($this->Menssajes->delete($menssaje)) {
            //$this->Flash->success(__('The menssaje has been deleted.'));
            $return['status']=1;
            $return['msj']='The Status message has been deleted';
        } else {
           //$this->Flash->error(__('The menssaje could not be deleted. Please, try again.'));
           $return['status']=0;
           $return['msj']='The Status message could not be delete Please, try again later.';
        }

        //return $this->redirect(['action' => 'index']);
        
        $this->set(compact('return'));
        $this->set('_serialize', ['return']);
    }
}
