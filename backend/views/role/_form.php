<?php
use yii\helpers\Html;
use antkaz\vue\VueAsset;
use xj\babel\BrowserAsset;
use yii\widgets\ActiveForm;

VueAsset::register($this); // register VueAsset
xj\babel\BrowserAsset::register($this);
?>
<style>
    .goods-create {
        overflow: visible!important;
    }
    .city--list.popup {
        top: 35px;
        max-height: 480px;
        overflow: auto;
    }

    #app label {
        display: flex;
        margin: 0;
    }
    #app  label span {
        flex-shrink: 0;
    }
    #app label input {
        width: unset!important;
    }
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
        opacity: 0;
    }
    .list-complete-item {
        transition: all 1s;
        display: inline-block;
        margin-right: 10px;
    }
    .list-complete-enter, .list-complete-leave-to
        /* .list-complete-leave-active for below version 2.1.8 */ {
        opacity: 0;
        transform: translateY(30px);
    }
    .list-complete-leave-active {
        position: absolute;
    }
    div,p{margin:0;padding:0; line-height:1.5;}
    .checks{ padding-left:20px;}

    .province--list, .city--list {
        display: flex;
        flex-wrap: wrap;
        padding-left: 10px;
        list-style-type: none;
    }

    .province--list > li {
        display: inline-flex;
        align-items: center;
        flex-shrink: 0;
        position: relative;
        margin-right: 10px;
        padding: 5px 10px;
    }

    .province--list > li .city--list {
        position: absolute;
        padding: 10px;
        background: #fff;
        z-index: 10000;
        box-shadow: 0 0 40px 10px rgba(0, 0, 0, .1);
        border-radius: 10px;
        /*opacity: 0;*/
        /*transition: all .2s;*/
    }

    /*.province--list > li.active {*/
    /*box-shadow: 0 0 40px 10px rgba(0, 0, 0, .1);*/
    /*}*/

    .province--list > li.active {
        color: #259c24;
    }
    .province--list > li.active .city--list {
        opacity: 1;

    }

    .province--list > li ul li {
        display: block;
        width: 200px;
        padding: 0;
    }

    input[type="checkbox"] {
        margin-right: 5px;
    }

    .glyphicon {
        padding: 5px;
        transition: all .2s;
        cursor: pointer;
    }

    .glyphicon.glyphicon-menu-down.switch--popup {
        position: relative;
        top: -1px;
    }
    .province--list > li.active .glyphicon {
        transform: rotate(180deg);
    }

    .shade {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1000;
    }
    label {
        font-weight: normal;
    }

    .display-checked ul li {
        position: relative;
        display: inline-block;
        padding: 5px 10px;
        margin: 5px;
        border-radius: 5px;
        list-style-type: none;
        background: #00a65a;
        color: #fff;
    }

    .display-checked ul li ul {
        position: absolute;
        left: 50%;
        width: 200px;
        padding: 10px;
        opacity: 0;
        background: #fff;
        z-index: 10000;
        box-shadow: 0 0 40px 10px rgba(0, 0, 0, .1);
        border-radius: 10px;
        transform: translateX(-50%);
        transition: all .2s;
    }

    .display-checked ul li:hover ul {
        opacity: 1;

    }

    .display-checked .close {
        float: none!important;
        padding: 0;
        font-size: 1em;
        color: #fff;
        opacity: 1;
        transition: all .2s;
    }

    .display-checked .close:active {
        transform: scale(.6);
    }
</style>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model,'name')->textInput($model->name?['disabled'=>'disabled']:[])->label('角色名称')?>
<script>
    var data = (<?= json_encode($data) ?>).map(i => ({
        province: i.name,
        city: i.children
    }))
</script>
<div id="app" class="vue">
    <div class="display-checked">
        <p>请勾选该角色所拥有的权限：</p>
        <ul>
            <li
                v-for="province in area.filter(i => i.city.find(j => j.checked))"
                :key="province.id"
                class="list-complete-item"
            >
                <div
                    class="close glyphicon glyphicon-remove"
                    title="删除"
                    @click="province.checked = false; province.city.forEach(i => i.checked = false)"
                ></div>
                <span>{{province.province}}</span>
                <!--                <ul>-->
                <!--                    <li>-->
                <!--                        <div class="close glyphicon glyphicon-remove" title="删除"></div>-->
                <!--                        <span>广东省</span>-->
                <!--                    </li>-->
                <!--                </ul>-->
            </li>
        </ul>
    </div>
    <div class="J_CheckWrap">
        <div v-if="activeProvince >= 0" @click="activeProvince = -1" class="shade" id="shade-layer"></div>
        <label class="check-all"><input type="checkbox" v-model="isCheckAll" /><span>全选</span></label>
        <div class="checks">
            <ul class="province--list">
                <li data-role="province" v-for="(province, index) in area">
                    <label data-type="province">
                        <input
                            type="checkbox"
                            v-model="province.checked"
                            @change="province.city.forEach(i => i.checked = province.checked)"
                        />
                        <span>{{province.province}}</span>
                    </label>
                    <div
                        class="glyphicon glyphicon-menu-down switch--popup"
                        @click="activeProvince = index"
                    ></div>
                    <transition name="fade">
                        <ul v-show="activeProvince === index" class="city--list popup">
                            <li class="checks" v-for="city in province.city">
                                <label>
                                    <input
                                        type="checkbox"
                                        :name="`permission[${city.id}]`"
                                        <?php // in_array($city->city_id,$cities)?'checked':'' ?>
                                        :value="city.name"
                                        v-model="city.checked"
                                        @change="province.id = permission[permission.length - 1].id + 1; province.checked = province.city.every(i => i.checked)"
                                    />{{city.name}}</label>
                            </li>
                        </ul>
                    </transition>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="box-footer">
    <?= Html::submitButton('', ['class' => 'btn btn-success btn-float glyphicon glyphicon-ok', 'title' => '保存']) ?>
    <?= Html::a('', 'index', ['class' => 'btn btn-info btn-float glyphicon glyphicon-remove', 'title' => '取消']) ?>
</div>

<?php ActiveForm::end(); ?>
<script>

    window.addEventListener('load',function () {
        var app = new Vue({
            el: '#app',
            data: {
                area: data.map(i => {
                    return {
                        ...i,
                        city: i.city.map(j => {return {...j, checked: false}}),
                        checked: false
                    }
                }),
                checkedAreaIds: (<?= json_encode($checkedData) ?>),
                activeProvince: -1
            },
            computed: {
                isCheckAll: {
                    get() {
                        return this.area.every(i => i.checked)
                    },
                    set(value) {
                        this.area.forEach(i => {
                            i.checked = value;
                            i.city.forEach(j => j.checked = value)
                        })
                    }
                }
            },
            methods: {
                clickProvince(index) {
                    for (let city in this.area[index].city) {
                        city.checked = this.area[index].checked
                    }
                },
                reverseMessage: function () {
                    this.message = this.message.split('').reverse().join('')
                }
            },
            created() {
                this.checkedAreaIds.length > 0 && this.area.forEach(province => {
                    let count = 0;
                    province.city.forEach(city => {
                        if (this.checkedAreaIds.indexOf(city.id.toString()) >= 0) {
                            city.checked = true;
                            count++;
                        }
                    });
                    if (count >= province.city.length) {
                        province.checked = true;
                    }
                })
                // document.body.addEventListener('click', e => {
                //     let isSwitch = e.target.classList.contains('switch--popup');
                //     let isCheckbox = e.target.type === 'checkbox';
                //     let isLabel = e.target.dataset.type === 'province';
                //
                //     if (this.activeProvince >= 0 && !isCheckbox && !isSwitch && !isLabel) {
                //         this.activeProvince = -1;
                //         e.stopPropagation();
                //     }
                // })
            }
        });
    })

</script>

<div>


<script type="text/javascript" src="/js/jquery.min.js"></script>
<!--<script >-->
<!--//-->
<!--//    (function (win, doc, $) {-->
<!--//        $.fn.extend({-->
<!--//            checktree : function () {-->
<!--//                this.click(function (evt) {-->
<!--//                    var evtEle = $(evt.target).closest("input:checkbox");-->
<!--//                    if (!evtEle[0]) {-->
<!--//                        return;-->
<!--//                    }-->
<!--//                    //check child all-->
<!--//                    evtEle.parent().next(".checks").find("input:checkbox").attr("checked", evtEle[0].checked);-->
<!--//-->
<!--//                    //check parent-->
<!--//                    if (evtEle.is("input:checked")) {-->
<!--//                        // evtEle.parents(".checks").each(function () {-->
<!--//                        //     !$(this).children("p").children("input:checkbox").filter(function () {-->
<!--//                        //         return !this.checked;-->
<!--//                        //     })[0] && $(this).prev().children("input:checkbox").attr("checked", "checked");-->
<!--//                        // });-->
<!--//                    } else {-->
<!--//                        evtEle.parents(".checks").prev().children("input:checkbox").attr("checked", false);-->
<!--//                    }-->
<!--//                });-->
<!--//            }-->
<!--//        });-->
<!--////        document.querySelector('.container-fluid.form-vertical').addEventListener('submit', function (e) {-->
<!--////-->
<!--////            alert('ggg');-->
<!--////            e.preventDefault();-->
<!--////            return false;-->
<!--////        })-->
<!--//    })(window, document, jQuery);-->
<!---->
<!--    function $(query) {-->
<!--        return document.querySelector(query);-->
<!--    }-->
<!---->
<!--    function $$(query) {-->
<!--        return document.querySelectorAll(query);-->
<!--    }-->
<!---->
<!--    var activeProvince = null;-->
<!---->
<!--    window.addEventListener('click', function (e) {-->
<!--        function $(query) {-->
<!--            return document.querySelector(query);-->
<!--        }-->
<!---->
<!--        if (e.target.type === 'checkbox') {-->
<!--            var parent = e.target.parentElement;-->
<!---->
<!--            if (parent.dataset.type === 'province') {-->
<!--                // 点击省复选框-->
<!--                // parent.parentElement.className = e.target.checked ? 'active' : '';-->
<!---->
<!--                var ul = parent.nextElementSibling;-->
<!--                var inputList = ul.querySelectorAll('input[type="checkbox"]');-->
<!---->
<!--                for (var i = 0, input; input = inputList[i++];) {-->
<!--                    input.checked = e.target.checked;-->
<!--                }-->
<!---->
<!--                var isCheckedAllProvince = Array.prototype.every.call($$('.checkbox-province'), i => i.checked);-->
<!---->
<!--                $('#check-all-province').checked = isCheckedAllProvince;-->
<!--            } else {-->
<!--                // 点击城市复选框-->
<!--                var parentUL = e.target.parentElement.parentElement.parentElement;-->
<!--                var inputList = parentUL.querySelectorAll('input');-->
<!--                var parentInput = parentUL.previousElementSibling.querySelector('input');-->
<!--                var isCheckedAllCity = Array.prototype.every.call(inputList, i => i.checked);-->
<!--                parentInput.checked = isCheckedAllCity;-->
<!--                var isCheckedAllProvince = Array.prototype.every.call($$('.checkbox-province'), i => i.checked);-->
<!--                $('#check-all-province').checked = isCheckedAllProvince;-->
<!--            }-->
<!--        }-->
<!--        if (e.target.className === 'glyphicon glyphicon-menu-down switch--popup') {-->
<!--            var parentLi = e.target.parentElement;-->
<!---->
<!--            if (parentLi.classList.contains('active')) {-->
<!--                parentLi.classList.remove('active');-->
<!--                activeProvince = null;-->
<!--                $('#shade-layer').className = ''-->
<!--            } else {-->
<!--                activeProvince && (activeProvince.className = '');-->
<!--                parentLi.classList.add('active');-->
<!--                activeProvince = parentLi;-->
<!--                // $('#shade-layer').className = 'shade'-->
<!--            }-->
<!--        } else {-->
<!--            function $(query) {-->
<!--                return document.querySelector(query);-->
<!--            }-->
<!--            activeProvince && (activeProvince.className = '');-->
<!--            activeProvince = null;-->
<!--            $('#shade-layer').className = '';-->
<!--        }-->
<!--    });-->
<!---->
<!--    $('#check-all-province').addEventListener('change', function (e) {-->
<!--        Array.prototype.forEach.call($$('.checkbox-province'), function (input) {-->
<!--            input.checked = e.target.checked;-->
<!--            var parent = input.parentElement;-->
<!---->
<!--            var ul = parent.nextElementSibling;-->
<!--            var inputList = ul.querySelectorAll('input[type="checkbox"]');-->
<!---->
<!--            for (var i = 0, item; item = inputList[i++];) {-->
<!--                item.checked = e.target.checked;-->
<!--            }-->
<!--        })-->
<!--    });-->
<!---->
<!--    // $('#shade-layer').addEventListener('click', function () {-->
<!--    //     function $(query) {-->
<!--    //         return document.querySelector(query);-->
<!--    //     }-->
<!--    //     activeProvince && (activeProvince.className = '');-->
<!--    //     activeProvince = null;-->
<!--    //     $('#shade-layer').className = '';-->
<!--    // });-->
<!---->
<!--</script>-->
<!--<script>-->
<!--    $(".J_CheckWrap").checktree();-->
<!--</script>-->
</div>