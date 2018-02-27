/*function getAvatar(avatar) {
    if(avatar == null) {
        return '/img/avatar.jpg';
    } else {
        return '/avi/' + avatar;
    }
}

then

<img src="' + getAvatar(data.user.avatar) + '" alt="">

*/


    var Cookie = {
        get : function ( name ) {
            var start = document.cookie.indexOf( name + "=" );
            var len = start + name.length + 1;
            if ( ( !start ) && ( name != document.cookie.substring( 0, name.length ) ) ) {
                return null;
            }
            if ( start == -1 ) return null;
            var end = document.cookie.indexOf( ';', len );
            if ( end == -1 ) end = document.cookie.length;
            return unescape( document.cookie.substring( len, end ) );
        }
}

console.log(Cookie.get('ID'));