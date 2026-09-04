var ua = {
  agent: "",
  os: "",
  name: "",
  match: "",
  ver: "",
  isTouchDevice: false,
  isIE8: false,
  isIE9: false,
  isIE10: false,
  isIE: false,
  isiPhone: false,
  isiPod: false,
  isiPad: false,
  isiOS: false,
  isAndroid: false,
  isSafari: false,
  isMac: false,
  isEdge: false,
  androidVer: 0,
  checkAgent: function () {
    ua.name = window.navigator.userAgent.toLowerCase();
    ua.isIE = (ua.name.indexOf("msie") >= 0 || ua.name.indexOf("trident") >= 0);
    ua.isiPhone = ua.name.indexOf("iphone") >= 0;
    ua.isiPod = ua.name.indexOf("ipod") >= 0;
    ua.isiPad = ua.name.indexOf("ipad") >= 0 || ua.name.indexOf('macintosh') > -1 && 'ontouchend' in document;;
    ua.isiOS = (ua.isiPhone || ua.isiPod || ua.isiPad);
    ua.isAndroid = ua.name.indexOf("android") >= 0;
    ua.isTablet = (ua.isiPad || (ua.isAndroid && ua.name.indexOf("mobile") < 0));
    ua.isTouchDevice = (ua.isiOS || ua.isAndroid || ua.isTablet);
    ua.verArray = /(os)\s([0-9]{1,})([\_0-9]{1,})/.exec(ua.name);
    console.log(ua.name,this.isTouchDevice);
    if (ua.verArray) {
      ua.ver = parseInt(ua.verArray[2], 10)
    }
    if (ua.isiPhone || ua.isiPod || ua.isAndroid) {
      ua.agent = "sp"
    } else {
      ua.agent = "pc"
    }
    if (ua.name.indexOf("safari") !== -1 && ua.name.indexOf("chrome") === -1) {
      ua.isSafari = true
    }
    ua.isMac = ua.name.indexOf("mac") != -1;
    console.log(ua.name.indexOf("applewebkit"), ua.name.indexOf("edge"));
    if (ua.name.indexOf("applewebkit") >= 0 && ua.name.indexOf("edge") >= 0) {
      ua.isEdge = true
    }
    if (navigator.userAgent.indexOf("MSIE 8.") != -1) {
      ua.isIE8 = true
    }
    if (navigator.userAgent.indexOf("MSIE 9.") != -1) {
      ua.isIE9 = true
    }
    if (navigator.userAgent.indexOf("MSIE 10.") != -1) {
      ua.isIE10 = true
    }
    if (navigator.userAgent.match(/Win(dows )?NT 6\.3/)) {
      ua.os = "Windows 8.1"
    } else {
      if (navigator.userAgent.match(/Win(dows )?NT 6\.2/)) {
        ua.os = "Windows 8"
      } else {
        if (navigator.userAgent.match(/Win(dows )?NT 6\.1/)) {
          ua.os = "Windows 7"
        } else {
          if (navigator.userAgent.match(/Win(dows )?NT 6\.0/)) {
            ua.os = "Windows Vista"
          } else {
            if (navigator.userAgent.match(/Win(dows )?NT 5\.2/)) {
              ua.os = "Windows Server 2003"
            } else {
              if (navigator.userAgent.match(/Win(dows )?(NT 5\.1|XP)/)) {
                ua.os = "Windows XP"
              } else {
                if (navigator.userAgent.match(/Win(dows)? (9x 4\.90|ME)/)) {
                  ua.os = "Windows ME"
                } else {
                  if (navigator.userAgent.match(/Win(dows )?(NT 5\.0|2000)/)) {
                    ua.os = "Windows 2000"
                  } else {
                    if (navigator.userAgent.match(/Win(dows )?98/)) {
                      ua.os = "Windows 98"
                    } else {
                      if (navigator.userAgent.match(/Win(dows )?NT( 4\.0)?/)) {
                        ua.os = "Windows NT"
                      } else {
                        if (navigator.userAgent.match(/Win(dows )?95/)) {
                          ua.os = "Windows 95"
                        } else {
                          if (navigator.userAgent.match(/Mac|PPC/)) {
                            ua.os = "Mac OS"
                          } else {
                            if (navigator.userAgent.match(/Linux/)) {
                              ua.os = "Linux"
                            } else {
                              if (navigator.userAgent.match(/^.*\s([A-Za-z]+BSD)/)) {
                                ua.os = RegExp.$1
                              } else {
                                if (navigator.userAgent.match(/SunOS/)) {
                                  ua.os = "Solaris"
                                } else {
                                  ua.os = "N/A"
                                }
                              }
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
    ua.isiOS = (ua.isiPhone || ua.isiPod || ua.isiPad);
    var a = navigator.userAgent;
    ua.isAndroid = a.indexOf("Android") >= 0;
    if (a.indexOf("Android") > 0) {
      ua.androidVer = parseFloat(a.slice(a.indexOf("Android") + 8))
    }
  }
};
