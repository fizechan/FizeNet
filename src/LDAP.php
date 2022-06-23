<?php
/**
 *  LDAP类
 *
 * 轻量级的目录访问协议
 * @todo 应用较少，有市场需要再进行编写
 */

namespace Fize\Net;

class LDAP
{
    public static function from8859Tot61($value)
    {
        return ldap_8859_to_t61($value);
    }
}