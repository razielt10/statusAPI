<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<div align="justify">To activate your Messaje, click below</div>
                                                        
<p align="center"><?php echo $this->Html->link('Activate Messaje',
        ['controller' => 'confirmation', 'action' => $code, '_full' => true], 
        ['target' => '_blank',
        'style' => 'text-decoration: none; color: rgb(255, 255, 255); padding: 10px 16px; font-weight: bold; margin-right: 10px; text-align: center; cursor: pointer; display: inline-block; background-color: #B21017; text-align:center;']);?></p>
