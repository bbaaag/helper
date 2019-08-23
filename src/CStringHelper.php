<?php
/**
 * Created by PhpStorm.
 * User: 123
 * Date: 2019/8/9
 * Time: 9:25
 */

namespace bbaaag\Hepler;


class CStringHelper
{


    /**
     * 使用多个分隔符 进行分割
     * @param $delimiters
     * @param $string
     * @return array
     * multie_xplode(array(",",".","|",":"),$text);
     */
    function multi_explode ($delimiters,$string) {

        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }


    /**
     * 按单词截取字符串
     * @param $string
     * @param $word_limit
     * @return string
     */
    function limit_words($string, $word_limit,  $append = '...')
    {

        $words = explode(" ",$string);
        return implode(" ",array_splice($words,0,$word_limit)).$append;
    }


    /**
     * getBetween($str,'(',')')
     * 获取两个字符之间的字符
     * @param $haystack
     * @param $start
     * @param $end
     * @return string
     */

    function getBetween($haystack, $start, $end)
    {
        return trim(strstr(strstr($haystack, $start), $end, true), $start . $end);
    }



    function hideString($str = 'hello', $to = '*', $start = 1, $end = 0) {
        $lenth = strlen($str) - $start - $end;
        $lenth = ($lenth < 0) ? 0 : $lenth;
        $to = str_repeat($to, $lenth);
        $str = substr_replace($str, $to, $start, $lenth);
        return $str;
    }


    /**
     *截取字符串  保留指定长度的字符串，以...结尾
     * @param $string
     * @param $length
     * @param string $append
     * @return string
     */

    public static function truncate($string, $length, $append = '...')
    {
        $ret        = mb_substr($string, 0, $length,'UTF-8');
        $last_space = mb_strrpos ($ret, ' ',0,'UTF-8');

        if ($last_space !== false && $string != $ret) {
            $ret     = mb_substr($ret, 0, $last_space,'UTF-8');
        }

        if ($ret != $string) {
            $ret .= $append;
        }

        return $ret;
    }





    function mbStrSplit ($string, $len=1) {
        $start = 0;
        $strlen = mb_strlen($string);
        while ($strlen) {
            $array[] = mb_substr($string,$start,$len,"utf8");
            $string = mb_substr($string, $len, $strlen,"utf8");
            $strlen = mb_strlen($string);
        }
        return $array;
    }


    /**
     * 判断是否以指定的字符串结束
     * @param $haystack
     * @param $needle
     * @return bool
     */
    function endsWith($haystack, $needle)
    {
        return strrpos($haystack, $needle) === (strlen($haystack) - strlen($needle));
    }

    /**
     * 判断是否以指定的字符串开始
     * @param $haystack
     * @param $needle
     * @return bool
     */

    function startsWith($haystack, $needle)
    {
        return strpos($haystack, $needle) === 0;
    }


    function wrap_link(){

    }

    /**
     * 将文本中的连接 替换成H5的 <a>连接
     * Turns all of the links in a string into HTML links.
     *
     * Part of the LinkifyURL Project <https://github.com/jmrware/LinkifyURL>
     *
     * @param  string $text The string to parse
     * @return string
     */
    public static function linkify($text)
    {
        $text = preg_replace('/&apos;/', '&#39;', $text); // IE does not handle &apos; entity!
        $section_html_pattern = '%# Rev:20100913_0900 github.com/jmrware/LinkifyURL
            # Section text into HTML <A> tags  and everything else.
              (                             # $1: Everything not HTML <A> tag.
                [^<]+(?:(?!<a\b)<[^<]*)*     # non A tag stuff starting with non-"<".
              |      (?:(?!<a\b)<[^<]*)+     # non A tag stuff starting with "<".
             )                              # End $1.
            | (                             # $2: HTML <A...>...</A> tag.
                <a\b[^>]*>                   # <A...> opening tag.
                [^<]*(?:(?!</a\b)<[^<]*)*    # A tag contents.
                </a\s*>                      # </A> closing tag.
             )                              # End $2:
            %ix';

        return preg_replace_callback($section_html_pattern, array(__CLASS__, 'linkifyCallback'), $text);
    }


    /**
     * Callback for the preg_replace in the linkify() method.
     *
     * Part of the LinkifyURL Project <https://github.com/jmrware/LinkifyURL>
     *
     * @param  array  $matches Matches from the preg_ function
     * @return string
     */
    protected static function linkifyCallback($matches)
    {
        if (isset($matches[2])) {
            return $matches[2];
        }

        return self::linkifyRegex($matches[1]);
    }


    /**
     * Callback for the preg_replace in the linkify() method.
     *
     * Part of the LinkifyURL Project <https://github.com/jmrware/LinkifyURL>
     *
     * @param  array  $matches Matches from the preg_ function
     * @return string
     */
    protected static function linkifyRegex($text)
    {
        $url_pattern = '/# Rev:20100913_0900 github.com\/jmrware\/LinkifyURL
            # Match http & ftp URL that is not already linkified.
            # Alternative 1: URL delimited by (parentheses).
            (\() # $1 "(" start delimiter.
            ((?:ht|f)tps?:\/\/[a-z0-9\-._~!$&\'()*+,;=:\/?#[\]@%]+) # $2: URL.
            (\)) # $3: ")" end delimiter.
            | # Alternative 2: URL delimited by [square brackets].
            (\[) # $4: "[" start delimiter.
            ((?:ht|f)tps?:\/\/[a-z0-9\-._~!$&\'()*+,;=:\/?#[\]@%]+) # $5: URL.
            (\]) # $6: "]" end delimiter.
            | # Alternative 3: URL delimited by {curly braces}.
            (\{) # $7: "{" start delimiter.
            ((?:ht|f)tps?:\/\/[a-z0-9\-._~!$&\'()*+,;=:\/?#[\]@%]+) # $8: URL.
            (\}) # $9: "}" end delimiter.
            | # Alternative 4: URL delimited by <angle brackets>.
            (<|&(?:lt|\#60|\#x3c);) # $10: "<" start delimiter (or HTML entity).
            ((?:ht|f)tps?:\/\/[a-z0-9\-._~!$&\'()*+,;=:\/?#[\]@%]+) # $11: URL.
            (>|&(?:gt|\#62|\#x3e);) # $12: ">" end delimiter (or HTML entity).
            | # Alternative 5: URL not delimited by (), [], {} or <>.
            (# $13: Prefix proving URL not already linked.
            (?: ^ # Can be a beginning of line or string, or
            | [^=\s\'"\]] # a non-"=", non-quote, non-"]", followed by
           ) \s*[\'"]? # optional whitespace and optional quote;
            | [^=\s]\s+ # or... a non-equals sign followed by whitespace.
           ) # End $13. Non-prelinkified-proof prefix.
            (\b # $14: Other non-delimited URL.
            (?:ht|f)tps?:\/\/ # Required literal http, https, ftp or ftps prefix.
            [a-z0-9\-._~!$\'()*+,;=:\/?#[\]@%]+ # All URI chars except "&" (normal*).
            (?: # Either on a "&" or at the end of URI.
            (?! # Allow a "&" char only if not start of an...
            &(?:gt|\#0*62|\#x0*3e); # HTML ">" entity, or
            | &(?:amp|apos|quot|\#0*3[49]|\#x0*2[27]); # a [&\'"] entity if
            [.!&\',:?;]? # followed by optional punctuation then
            (?:[^a-z0-9\-._~!$&\'()*+,;=:\/?#[\]@%]|$) # a non-URI char or EOS.
           ) & # If neg-assertion true, match "&" (special).
            [a-z0-9\-._~!$\'()*+,;=:\/?#[\]@%]* # More non-& URI chars (normal*).
           )* # Unroll-the-loop (special normal*)*.
            [a-z0-9\-_~$()*+=\/#[\]@%] # Last char can\'t be [.!&\',;:?]
           ) # End $14. Other non-delimited URL.
            /imx';

        $url_replace = '$1$4$7$10$13<a href="$2$5$8$11$14">$2$5$8$11$14</a>$3$6$9$12';

        return preg_replace($url_pattern, $url_replace, $text);
    }





}