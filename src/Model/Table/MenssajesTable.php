<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Validation\Validation;
use EmailQueue\EmailQueue;

/**
 * Menssajes Model
 *
 * @method \App\Model\Entity\Menssaje get($primaryKey, $options = [])
 * @method \App\Model\Entity\Menssaje newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Menssaje[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Menssaje|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Menssaje patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Menssaje[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Menssaje findOrCreate($search, callable $callback = null)
 */
class MenssajesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('menssajes');
        $this->displayField('status');
        $this->primaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->allowEmpty('id', 'create');
        $validator
            ->add( 'id', [
                'err' => [
                'rule' => 'numeric', 
                'message' => '400002',
                ]
            ]);

        $validator
            ->add('email', 'message', [
                'rule' => 'email',
                'message' => '400005'])
            //->requirePresence('email', 'create')
            //->notEmpty('email')            
            ->allowEmpty('email');

        $validator
            ->requirePresence('status', 'create')
            ->notEmpty('status', '400007')
            //->notEmpty('status')
            ->add('status', [
                'message' => [
                'rule' => ['maxLength', 120],
                'message' => '400006',
                ]
            ])
            ;

        $validator
            ->dateTime('created_at')
            ->notEmpty('created_at');

        return $validator;
    }

    public function validationQueryParamaters(Validator $validator)
    {

        $validator
            ->add('r',[
                'code' => [
                    'rule'=> ['numeric'], 
                    'message'=>'400002',
                    'allowEmpty' => true,
                ]
            ])
            ->allowEmpty('r');
        $validator
            ->add('p',[
                'code' => [
                    'rule'=> ['numeric'], 
                    'message'=>'400003',
                    'allowEmpty' => true,
                ]
            ])
            ->allowEmpty('p');
        $validator
            ->add('q',[
                'code' => [
                    'rule'=> array('custom', '/^[a-z0-9]*$/i'),
                    'message'=>'400004',
                    'allowEmpty' => true,
                ]
            ])
            ->allowEmpty('q');
        
        return $validator;
    
    } 

    public function validationIdStatus(Validator $validator)
    {

        $validator
            ->add('id',[
                'code' => [
                    'rule'=> ['numeric'], 
                    'message'=>'400002',
                ]
            ]);
        $validator ->notEmpty('id');
        
        return $validator;
    
    }

    public function validationIdStatusDelete(Validator $validator)
    {

        $validator
            ->add('0',[
                'code' => [
                    'rule'=> ['numeric'], 
                    'message'=>'400002',
                ]
            ]);
        $validator ->notEmpty('0');
        
        return $validator;
    
    }

    public function validationCode(Validator $validator)
    {

        $validator
            ->add('code',[
                'code' => [
                    'rule'=> array('custom', '/^[a-z0-9]*$/i'),
                    'message'=>'400007',
                ]
            ]);
        $validator ->notEmpty('code', '400007');
        
        return $validator;
    
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['id']));

        return $rules;
    }
}
