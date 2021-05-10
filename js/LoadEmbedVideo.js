/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function showMyEmbedVideo(urlTag, videoTag, width = 480, height = 320) {
    if (urlTag !== null || urlTag !== '') {
        if (urlTag.tagName !== undefined) {
            if (urlTag.tagName !== 'INPUT') {
                return null;
            }
        } else {
            urlTag = document.getElementById(urlTag);
        }
    }
    if (videoTag !== null || videoTag !== '') {
        if (videoTag.tagName !== undefined) {
            if (videoTag.tagName !== 'DIV') {
                return null;
            }
        } else {
            videoTag = document.getElementById(videoTag);
        }
    }

    if (urlTag !== null && videoTag !== null) {
        if (urlTag.value !== '' && urlTag.value.toString().includes('youtube.com')) {
            console.log('YouTube Video');
            if (urlTag.value.toString().includes('watch?v=')) {
                urlTag.value = urlTag.value.toString().replace('watch?v=', 'embed/');
            }
        }
        
        var myiframe = document.createElement('IFRAME');
        myiframe.setAttribute('id', 'iframe' + videoTag.id);
        myiframe.setAttribute('name', 'iframe' + videoTag.id);
        myiframe.setAttribute('width', width);
        myiframe.setAttribute('height', height);
        myiframe.setAttribute('frameborder', '0');
        myiframe.setAttribute('allow', 'accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
        myiframe.setAttribute('allowfullscreen','allowfullscreen');
        myiframe.setAttribute('src', '' + urlTag.value);
        myiframe.setAttribute('style','max-width: 640px !important; max-height: 480px !important;');
        videoTag.innerHTML='';
        videoTag.appendChild(myiframe);
}
}

