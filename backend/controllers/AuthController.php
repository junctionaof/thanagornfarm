<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\AuthAssignment;
use common\models\AuthItem;
use common\models\AuthItemChild;
use common\models\AuthRole;
use common\models\User;



class AuthController extends Controller {
	
	public function actionInit() {
		
		$auth = \Yii::$app->authManager;
		$auth->removeAll();
		
		//$arrPerm = [];
		$query = AuthItem::find()->where(['type' => 0]);
		$modelAuthItem = $query->all();
		foreach($modelAuthItem as $obj) {
			$arrPerm[$obj->name] = $obj->description;
		}
		//$arrRole = [];
		$query = AuthItem::find()->where(['type' => 2]);
		$modelAuthItem = $query->all();
		foreach($modelAuthItem as $obj) {
			$arrRole[$obj->name] = $obj->description;
		}
		
		$query = AuthItem::find()->where(['type' => 2]);
		$modelAuthItem = $query->all();
		//$arrRolePerm = [];
			foreach($modelAuthItem as $obj) {
				$name = $obj->name;
				$query = AuthItemChild::find()->where(['parent' => $name]);
				$modelAuthItemChild = $query->all();			
					foreach($modelAuthItemChild as $objchild) {
						$arrRolePerm[$name][] = $objchild->child ;

					}
			}
		
			$auth->removeAll();
			
			foreach($arrPerm as $permName => $title) {
				$perm = $auth->createPermission($permName);
				$perm->description = $title;
				$auth->add($perm);
			}
			
			foreach($arrRole as $roleName => $title) {
				$role = $auth->createRole($roleName);
				$role->description = $title;
				$auth->add($role);
				// assign role permission
					foreach($arrRolePerm[$roleName] as $permName) {
						$perm = $auth->getPermission($permName);
						$auth->addChild($role, $perm);
					}
				}
			}
			
			public static function reassign() {
				$auth = \Yii::$app->authManager;
				$auth->removeAllAssignments();
				// all assignment
				$query = AuthAssignment::find();
				$model = $query->all();
				foreach($model as $list) {
					$auth->assign($auth->getRole($list->itemname), 'u:' . $list->userid);
				}
			}
			}
				