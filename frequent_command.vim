
" +-----------------------------------------
" | Common
" +-----------------------------------------
" | Frequent functions in my CODE career
" +-----------------------------------------
" | Author: Everyone  you & me(Dale)
" +----------------------------------------
:%s= *$==	"将所有行尾多余的空格删除
:g/^s*$/d	"将所有不包含字符(空格也不包含)的空行删除.
:%!xxd		"转化成16进制显示
:%!xxd -r	"恢复字符显示
		"替换部分匹配换行的是 \r 不是 \n，和查找不一样
:%s/\(\w\+\), \(\w\+\)/\2 \1/   将 Doe, John 修改为 John Doe
