let playBtn = document.querySelectorAll('.playlist .box-container .box .play');
let musicPlayer = document.querySelector('.music-player');
let musicTitle = musicPlayer.querySelector('.title');
//let musicAlbum = musicPlayer.querySelector('.album');
let musicName = musicPlayer.querySelector('.name');
let musicArtist = musicPlayer.querySelector('.artist');
//let music = musicPlayer.querySelector('.music');


window.RufflePlayer = window.RufflePlayer || {};
//console.log(getPublicDir());
//window.RufflePlayer.config.publicPath = getPublicDir();
window.RufflePlayer.config = {
    // Options affecting the whole page
    "publicPath": undefined,
    "polyfills": true,

    // Options affecting files only
    "allowScriptAccess": false,
    "autoplay": "auto",
    "unmuteOverlay": "visible",
    "backgroundColor": null,
    "wmode": "window",
    "letterbox": "fullscreen",
    "warnOnUnsupportedContent": true,
    "contextMenu": "on",
    "showSwfDownload": false,
    "upgradeToHttps": window.location.protocol === "https:",
    "maxExecutionDuration": 15,
    "logLevel": "error",
    "base": null,
    "menu": true,
    "salign": "",
    "forceAlign": false,
    "scale": "showAll",
    "forceScale": false,
    "frameRate": null,
    "quality": "high",
    "splashScreen": true,
    "preferredRenderer": null,
    "openUrlMode": "allow",
    "allowNetworking": "all",
    "favorFlash": true,
};

const boxDiv = document.createElement('div');
var ruffle = null;
var player = null;

window.addEventListener("load", () => {
    ruffle = window.RufflePlayer.newest();
    player = ruffle.createPlayer();
});

playBtn.forEach(play =>{

      play.onclick = () =>{

         boxDiv.classList.add("box");

         const titleDiv = document.createElement('div');
         titleDiv.classList.add("title");
         boxDiv.appendChild(titleDiv);

         //const imgDiv = document.createElement('img');
         //imgDiv.classList.add("album");
         //boxDiv.appendChild(imgDiv);

         const nameDiv = document.createElement('div');
         nameDiv.classList.add("name");
         boxDiv.appendChild(nameDiv);

         const artistDiv = document.createElement('div');
         artistDiv.classList.add("artist");
         boxDiv.appendChild(artistDiv);

         ////////audio//////
         // audioElement.classList.add("music");
         // audioElement.setAttribute("controls", null);
         // audioElement.controlsList = "noplaybackrate nodownload";
         // boxDiv.appendChild(audioElement);

         player.classList.add("music");
         boxDiv.appendChild(player);
         ////////audio//////
         
         let src = play.getAttribute('data-src');
         let box = play.parentElement.parentElement;
         let name = box.querySelector('.name');
         let title = box.querySelector('.title');
         //let album = box.querySelector('.album');
         let artist = box.querySelector('.artist');

         ////////ruffle//////
         // musicPlayer.appendChild(boxDiv);
         // imgDiv.src = album.src;
         // titleDiv.innerHTML = title.innerHTML;
         // nameDiv.innerHTML = name.innerHTML;
         // artistDiv.innerHTML = artist.innerHTML;
         // audioElement.src = src;

         musicPlayer.appendChild(boxDiv);
         //imgDiv.src = album.src;
         titleDiv.innerHTML = title.innerHTML;
         nameDiv.innerHTML = name.innerHTML;
         artistDiv.innerHTML = artist.innerHTML;
         //player.src = src;
         ////////ruffle//////
   
         musicPlayer.classList.add('active');

         player.load(src).then(() => {
            console.info("Ruffle successfully loaded the file");
            //it.play();
         }).catch((e) => {
               console.error(`Ruffle failed to load the file: ${e}`);
         });
      }
});

document.querySelector('#close').onclick = () =>{
   musicPlayer.classList.remove('active');
   player.pause();
   boxDiv.innerHTML = "";
}
