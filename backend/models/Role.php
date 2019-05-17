<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use mdm\admin\components\Configs;
use yii\db\Exception;
use yii\rbac\Item;

/**
 * AuthItemSearch represents the model behind the search form about AuthItem.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Role extends Model
{

    public $name;
    public $type;
    public $description;
    public $ruleName;
    public $data;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'unique'],
            [['ruleName', 'description'], 'safe'],
            [['type'], 'integer'],
        ];
    }

    /**
     * @param $name
     * @return array
     * 获取已选取权限
     */
    public function getCheckedPermission($name): array
    {
        $checkedData = [];
        $auth = Yii::$app->authManager;
        $checkedDataObj = $auth->getPermissionsByRole($name);
        foreach ($checkedDataObj as $route => $obj) {
            $checkedData[] = $route;
        }
        return $checkedData;
    }

    /**
     * @param $name
     * @throws Exception
     * 删除角色旧权限
     */
    private function removeOldPermission($name)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($name);
        if (!$auth->removeChildren($role)) {
            throw new Exception('删除旧权限失败');
        }
    }

    /**
     * @return array
     * 获取所有系统权限
     */
    public function getAllPermission()
    {
        $data = [];
        $permission = include(Yii::getAlias("@console/config/permission.php"));
        foreach ($permission as $k => $v) {
            $children = [];
            if ($k == 'dev' || $k == 'rbac') {
                continue;
            }
            $array['name'] = $k;
            foreach ($v as $key => $value) {
                $child['id'] = $value;
                $child['name'] = $key;
                $children[] = $child;
            }
            $array['children'] = $children;
            $data[] = $array;
        }
        return $data;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'item_name' => '',
            'type' => '',
            'description' => '权限',
            'ruleName' => '规则',
            'data' => '',
        ];
    }

    public function createOrUpdateRole($name = '')
    {
        $tra = Yii::$app->db->beginTransaction();
        try {
            $auth = Yii::$app->authManager;
            $name = $name?:Yii::$app->request->post('Role')['name'];
            if ($auth->getRole($name)) {
                $oRole = $auth->getRole($name);
                $this->removeOldPermission($name);
            } else {
                $oRole = $auth->createRole($name);
                $auth->add($oRole);
            }
            $permission = [];
            foreach (Yii::$app->request->post('permission') as $key => $value) {
                $oPermission = $auth->getPermission($key);
                if (!$oPermission) {
                    continue;
                }
                $auth->addChild($oRole, $oPermission);
                $permission[] = $value;
            }
            $oRole->description = implode(',', $permission);
            $auth->update($name, $oRole);
            $tra->commit();
            return ['status' => true];
        } catch (\Exception $e) {
            $tra->rollBack();
            return ['status' => false, 'info' => $e->getMessage()];
        }
    }

    /**
     * Search authitem
     * @param array $params
     * @return \yii\data\ActiveDataProvider|\yii\data\ArrayDataProvider
     */
    public function search($params)
    {
        /* @var \yii\rbac\Manager $authManager */
        $authManager = Configs::authManager();
        if ($this->type == Item::TYPE_ROLE) {
            $items = $authManager->getRoles();
        } else {
            $items = array_filter($authManager->getPermissions(), function ($item) {
                return $this->type == Item::TYPE_PERMISSION xor strncmp($item->name, '/', 1) === 0;
            });
        }
        $this->load($params);
        if ($this->validate()) {

            $search = mb_strtolower(trim($this->name));
            $desc = mb_strtolower(trim($this->description));
            $ruleName = $this->ruleName;
            foreach ($items as $name => $item) {
                $f = (empty($search) || mb_strpos(mb_strtolower($item->name), $search) !== false) &&
                    (empty($desc) || mb_strpos(mb_strtolower($item->description), $desc) !== false) &&
                    (empty($ruleName) || $item->ruleName == $ruleName);
                if (!$f) {
                    unset($items[$name]);
                }
            }
        }

        return new ArrayDataProvider([
            'allModels' => $items,
        ]);
    }
}
