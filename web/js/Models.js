function formatString(s,args)
{
    for (var i = 0; i < args.length; i++) {
        var reg = new RegExp("\\{" + i + "\\}","gm");
        s = s.replace(reg, args[i]);
    }
    return s;
}
var searchResult = `<ul class="notification-list friend-requests">
                            <li>
                                <div class="author-thumb">
                                    <img src="{0}" alt="author" style="width: 36px;height: 36px">
                                </div>
                                <div class="notification-event">
                                    <a href="{1}" class="h6 notification-friend">{2}</a>
                                    <span class="chat-message-item">{3}</span>
                                </div>
                                <span class="notification-icon" {4}>
                                    <a id="de{5}" value="{6}" href="#" class="accept-request">
                                        <span class="icon-add">
                                            <svg class="olymp-happy-face-icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="{7}"></use></svg>
                                        </span>
                                        Demande de Communication
                                    </a>
						        </span>
                            </li>
                        </ul>`;

