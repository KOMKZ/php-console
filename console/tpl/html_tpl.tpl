<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://unpkg.com/purecss@0.6.1/build/pure-min.css" integrity="sha384-CCTZv2q9I9m3UOxRLaJneXrrqKwUNOzZ6NGEUMwHtShDJ+nCoiXJCAgi05KfkLGY" crossorigin="anonymous">
    <title>Document</title>
    <style>
        body{
            text-align: center;
        }
        table{
            margin:20px 0px;
        }
        table tr td.des{
            text-align: left;
        }
    </style>
</head>
<body>
    <table class="pure-table pure-table-bordered">
        <thead>
            <tr>
                <th colspan="5">折扣的模式od_discount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>值</td>
                <td>意义</td>
                <td>标志</td>
                <td>描述</td>
                <td>是否默认</td>
            </tr>
            <tr>
    <td>0</td>
    <td>没有折扣</td>
    <td></td>
    <td  class="des"></td>
    <td>是</td>
</tr>
        </tbody>
</table><table class="pure-table pure-table-bordered">
        <thead>
            <tr>
                <th colspan="5">回赠的模式od_gift</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>值</td>
                <td>意义</td>
                <td>标志</td>
                <td>描述</td>
                <td>是否默认</td>
            </tr>
            <tr>
    <td>0</td>
    <td>没有回赠</td>
    <td></td>
    <td  class="des"></td>
    <td>是</td>
</tr>
        </tbody>
</table><table class="pure-table pure-table-bordered">
        <thead>
            <tr>
                <th colspan="5">退款的模式od_refund</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>值</td>
                <td>意义</td>
                <td>标志</td>
                <td>描述</td>
                <td>是否默认</td>
            </tr>
            <tr>
    <td>0</td>
    <td>默认退款模式</td>
    <td></td>
    <td  class="des"></td>
    <td>是</td>
</tr>
        </tbody>
</table><table class="pure-table pure-table-bordered">
        <thead>
            <tr>
                <th colspan="5">货币的类型od_price_type</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>值</td>
                <td>意义</td>
                <td>标志</td>
                <td>描述</td>
                <td>是否默认</td>
            </tr>
            <tr>
    <td>1</td>
    <td>人民币</td>
    <td></td>
    <td  class="des"></td>
    <td>是</td>
</tr>
        </tbody>
</table><table class="pure-table pure-table-bordered">
        <thead>
            <tr>
                <th colspan="5">订单的类型od_type</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>值</td>
                <td>意义</td>
                <td>标志</td>
                <td>描述</td>
                <td>是否默认</td>
            </tr>
            <tr>
    <td>1</td>
    <td>普通订单</td>
    <td></td>
    <td  class="des"></td>
    <td>是</td>
</tr><tr>
    <td>2</td>
    <td>合并订单</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>3</td>
    <td>子订单</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>4</td>
    <td>分期订单</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr>
        </tbody>
</table><table class="pure-table pure-table-bordered">
        <thead>
            <tr>
                <th colspan="5">订单的状态od_status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>值</td>
                <td>意义</td>
                <td>标志</td>
                <td>描述</td>
                <td>是否默认</td>
            </tr>
            <tr>
    <td>1</td>
    <td>未支付</td>
    <td></td>
    <td  class="des"></td>
    <td>是</td>
</tr><tr>
    <td>2</td>
    <td>已经完成</td>
    <td></td>
    <td  class="des">代表支付成功</td>
    <td></td>
</tr><tr>
    <td>21</td>
    <td>已评价</td>
    <td></td>
    <td  class="des">查询到该用户已经对该产品进行评分</td>
    <td></td>
</tr><tr>
    <td>4</td>
    <td>已经取消</td>
    <td></td>
    <td  class="des">订单关联商品发生明显的变化，会导致订单的商品不能使用，故会导致商品进行改动<br/>后台管理操作取消订单<br/>订单过期 od_expire_time字段导致</td>
    <td></td>
</tr><tr>
    <td>5</td>
    <td>发生过退款</td>
    <td></td>
    <td  class="des">参考od_refund_status了解更详细的退款状态细节</td>
    <td></td>
</tr>
        </tbody>
</table><table class="pure-table pure-table-bordered">
        <thead>
            <tr>
                <th colspan="5">订单的退款状态od_refund_status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>值</td>
                <td>意义</td>
                <td>标志</td>
                <td>描述</td>
                <td>是否默认</td>
            </tr>
            <tr>
    <td>1</td>
    <td>退款申请中</td>
    <td></td>
    <td  class="des">买家提交一条退款申请</td>
    <td>是</td>
</tr><tr>
    <td>21</td>
    <td>卖家同意退款</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>22</td>
    <td>平台同意退款</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>31</td>
    <td>卖家发起退款申诉</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>31</td>
    <td>平台同意拒绝退款</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr>
        </tbody>
</table><table class="pure-table pure-table-bordered">
        <thead>
            <tr>
                <th colspan="5">订单的支付类型od_pay_type</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>值</td>
                <td>意义</td>
                <td>标志</td>
                <td>描述</td>
                <td>是否默认</td>
            </tr>
            <tr>
    <td>wxpay</td>
    <td>微信支付</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>alipay</td>
    <td>支付宝支付</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>balance_pay</td>
    <td>余额支付</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr>
        </tbody>
</table><table class="pure-table pure-table-bordered">
        <thead>
            <tr>
                <th colspan="5">支付单的支付类型po_pay_type</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>值</td>
                <td>意义</td>
                <td>标志</td>
                <td>描述</td>
                <td>是否默认</td>
            </tr>
            <tr>
    <td>wxpay</td>
    <td>微信支付</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>alipay</td>
    <td>支付宝支付</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>balance_pay</td>
    <td>余额支付</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr>
        </tbody>
</table><table class="pure-table pure-table-bordered">
        <thead>
            <tr>
                <th colspan="5">商品的状态g_status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>值</td>
                <td>意义</td>
                <td>标志</td>
                <td>描述</td>
                <td>是否默认</td>
            </tr>
            <tr>
    <td>1</td>
    <td>有效状态</td>
    <td></td>
    <td  class="des"></td>
    <td>是</td>
</tr><tr>
    <td>2</td>
    <td>无效状态</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr>
        </tbody>
</table><table class="pure-table pure-table-bordered">
        <thead>
            <tr>
                <th colspan="5">提款申请的状态fwa_status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>值</td>
                <td>意义</td>
                <td>标志</td>
                <td>描述</td>
                <td>是否默认</td>
            </tr>
            <tr>
    <td>1</td>
    <td>等待确认状态</td>
    <td></td>
    <td  class="des"></td>
    <td>是</td>
</tr><tr>
    <td>2</td>
    <td>申请拒绝状态</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>3</td>
    <td>管理员已经确认状态</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>4</td>
    <td>提款已经完成状态</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>5</td>
    <td>管理员已经确认且提款已经完成状态</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr>
        </tbody>
</table><table class="pure-table pure-table-bordered">
        <thead>
            <tr>
                <th colspan="5">退款申请的状态ra_status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>值</td>
                <td>意义</td>
                <td>标志</td>
                <td>描述</td>
                <td>是否默认</td>
            </tr>
            <tr>
    <td>1</td>
    <td>退款申请中</td>
    <td></td>
    <td  class="des">买家提交一条退款申请</td>
    <td>是</td>
</tr><tr>
    <td>21</td>
    <td>卖家同意退款</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>22</td>
    <td>平台同意退款</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>31</td>
    <td>卖家发起退款申诉</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>31</td>
    <td>平台同意拒绝退款</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr>
        </tbody>
</table><table class="pure-table pure-table-bordered">
        <thead>
            <tr>
                <th colspan="5">账单的状态fb_status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>值</td>
                <td>意义</td>
                <td>标志</td>
                <td>描述</td>
                <td>是否默认</td>
            </tr>
            <tr>
    <td>1</td>
    <td>未付款</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>2</td>
    <td>已经完成</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>3</td>
    <td>已经取消</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr>
        </tbody>
</table><table class="pure-table pure-table-bordered">
        <thead>
            <tr>
                <th colspan="5">账单的类型fb_fee_type</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>值</td>
                <td>意义</td>
                <td>标志</td>
                <td>描述</td>
                <td>是否默认</td>
            </tr>
            <tr>
    <td>1</td>
    <td>消费型账单</td>
    <td></td>
    <td  class="des"></td>
    <td>是</td>
</tr><tr>
    <td>2</td>
    <td>退款型账单</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>3</td>
    <td>平台服务费型账单</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr>
        </tbody>
</table><table class="pure-table pure-table-bordered">
        <thead>
            <tr>
                <th colspan="5">交易的类型fb_app_trade_type</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>值</td>
                <td>意义</td>
                <td>标志</td>
                <td>描述</td>
                <td>是否默认</td>
            </tr>
            <tr>
    <td>1</td>
    <td>消费型账单</td>
    <td></td>
    <td  class="des"></td>
    <td>是</td>
</tr><tr>
    <td>2</td>
    <td>退款型账单</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr>
        </tbody>
</table><table class="pure-table pure-table-bordered">
        <thead>
            <tr>
                <th colspan="5">账户的类型fa_type</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>值</td>
                <td>意义</td>
                <td>标志</td>
                <td>描述</td>
                <td>是否默认</td>
            </tr>
            <tr>
    <td>1</td>
    <td>普通账户</td>
    <td></td>
    <td  class="des"></td>
    <td>是</td>
</tr><tr>
    <td>2</td>
    <td>公司账户</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>3</td>
    <td>平台账户</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr>
        </tbody>
</table><table class="pure-table pure-table-bordered">
        <thead>
            <tr>
                <th colspan="5">账户的状态fa_status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>值</td>
                <td>意义</td>
                <td>标志</td>
                <td>描述</td>
                <td>是否默认</td>
            </tr>
            <tr>
    <td>1</td>
    <td>正常状态</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>2</td>
    <td>允许查询状态</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>3</td>
    <td>系统锁定状态</td>
    <td></td>
    <td  class="des">不能查询<br/>不能提款</td>
    <td></td>
</tr><tr>
    <td>4</td>
    <td>废弃状态</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>5</td>
    <td>异常状态</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr>
        </tbody>
</table><table class="pure-table pure-table-bordered">
        <thead>
            <tr>
                <th colspan="5">银行卡的状态fab_status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>值</td>
                <td>意义</td>
                <td>标志</td>
                <td>描述</td>
                <td>是否默认</td>
            </tr>
            <tr>
    <td>1</td>
    <td>可用状态</td>
    <td></td>
    <td  class="des"></td>
    <td>是</td>
</tr><tr>
    <td>2</td>
    <td>废弃状态</td>
    <td></td>
    <td  class="des">如用户删除会导致这个状态</td>
    <td></td>
</tr><tr>
    <td>3</td>
    <td>失效状态</td>
    <td></td>
    <td  class="des">用户设置会该卡暂时不生效时可用</td>
    <td></td>
</tr>
        </tbody>
</table><table class="pure-table pure-table-bordered">
        <thead>
            <tr>
                <th colspan="5">银行卡的类别fab_card_type</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>值</td>
                <td>意义</td>
                <td>标志</td>
                <td>描述</td>
                <td>是否默认</td>
            </tr>
            <tr>
    <td>CC</td>
    <td>信用卡</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>DC</td>
    <td>储蓄卡</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr>
        </tbody>
</table><table class="pure-table pure-table-bordered">
        <thead>
            <tr>
                <th colspan="5">应用标志fb_app_id</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>值</td>
                <td>意义</td>
                <td>标志</td>
                <td>描述</td>
                <td>是否默认</td>
            </tr>
            <tr>
    <td>1</td>
    <td>安全家应用</td>
    <td></td>
    <td  class="des"></td>
    <td>是</td>
</tr><tr>
    <td>2</td>
    <td>泰致德应用</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr><tr>
    <td>3</td>
    <td>交通安全应用</td>
    <td></td>
    <td  class="des"></td>
    <td></td>
</tr>
        </tbody>
</table>
</body>
</html>
