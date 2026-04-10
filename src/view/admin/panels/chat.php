<main>
    <div id="chat_wrapper">
        <div id="chat_messages">
            <?php foreach ($messages as $msg): ?>
                <?php
                $isMine = ((int) $_SESSION['userid'] === (int) ($msg['id_membre'] ?? 0));
                $pp = $msg['pp_membre'];
                $ppSrc = $pp ? 'assets/image/api/pp/' . htmlspecialchars($pp) : 'assets/image/admin/default_images/user.jpg';
                $name = htmlspecialchars($msg['prenom_membre'] . ' ' . strtoupper($msg['nom_membre']));
                $time = htmlspecialchars(date('H:i', strtotime($msg['created_at'])));
                ?>
                <div class="chat_message <?= $isMine ? 'mine' : 'other' ?>" data-id="<?= $msg['id_message'] ?>">
                    <?php if (!$isMine): ?>
                        <img class="chat_avatar" src="<?= $ppSrc ?>" alt="<?= $name ?>">
                    <?php endif; ?>
                    <div class="chat_bubble_wrap">
                        <?php if (!$isMine): ?>
                            <span class="chat_author"><?= $name ?></span>
                        <?php endif; ?>
                        <div class="chat_bubble"><?= nl2br(htmlspecialchars($msg['message'])) ?></div>
                        <span class="chat_time"><?= $time ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <form id="chat_form" method="POST" action="/?page=admin-admin/chat/send">
            <input type="text" id="chat_input" name="message" placeholder="Écrire un message..." autocomplete="off"
                maxlength="1000">
            <button type="submit" class="btn-transparent btn-blue">
                <span class="material-symbols-outlined">send</span>Envoyer
            </button>
        </form>
    </div>
</main>

<style>
    #chat_wrapper {
        display: flex;
        flex-direction: column;
        height: 100%;
        padding: 20px;
        gap: 15px;
    }

    #chat_messages {
        flex: 1;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding-right: 5px;
    }

    .chat_message {
        display: flex;
        align-items: flex-end;
        gap: 10px;
    }

    .chat_message.mine {
        flex-direction: row-reverse;
    }

    .chat_avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }

    .chat_bubble_wrap {
        display: flex;
        flex-direction: column;
        gap: 3px;
        max-width: 60%;
    }

    .chat_message.mine .chat_bubble_wrap {
        align-items: flex-end;
    }

    .chat_author {
        font-size: 12px;
        color: var(--text-lighter);
    }

    .chat_bubble {
        background-color: var(--background-color-whiter);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 10px 14px;
        font-size: 14px;
        line-height: 1.4;
        word-break: break-word;
    }

    .chat_message.mine .chat_bubble {
        background-color: #63CA60;
        color: white;
        border-color: #63CA60;
    }

    .chat_time {
        font-size: 11px;
        color: var(--text-lighter);
        padding: 0 4px;
    }

    #chat_form {
        display: flex;
        flex-direction: row;
        gap: 10px;
        flex-shrink: 0;
    }

    #chat_input {
        flex: 1;
    }
</style>

<script>
    (function () {
        const container = document.getElementById('chat_messages');
        const form = document.getElementById('chat_form');
        const input = document.getElementById('chat_input');
        const myId = <?= (int) $_SESSION['userid'] ?>;
        let lastId = <?= (int) $lastId ?>;

        function scrollToBottom() {
            container.scrollTop = container.scrollHeight;
        }

        function renderMessage(msg) {
            const isMine = msg.id_membre == myId;
            const pp = msg.pp_membre
                ? 'assets/image/api/pp/' + msg.pp_membre
                : 'assets/image/default_images/user.jpg';
            const name = msg.prenom_membre + ' ' + msg.nom_membre.toUpperCase();
            const time = msg.created_at.substring(11, 16);
            const text = msg.message.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br>');

            const div = document.createElement('div');
            div.className = 'chat_message ' + (isMine ? 'mine' : 'other');
            div.dataset.id = msg.id_message;

            let html = '';
            if (!isMine) {
                html += `<img class="chat_avatar" src="${pp}" alt="${name}">`;
            }
            html += `<div class="chat_bubble_wrap">`;
            if (!isMine) {
                html += `<span class="chat_author">${name}</span>`;
            }
            html += `<div class="chat_bubble">${text}</div>`;
            html += `<span class="chat_time">${time}</span>`;
            html += `</div>`;

            div.innerHTML = html;
            return div;
        }

        async function poll() {
            try {
                const res = await fetch(`/?page=admin-admin/chat/poll&after=${lastId}`);
                if (!res.ok) return;
                const msgs = await res.json();
                if (msgs.length > 0) {
                    const wasAtBottom = container.scrollHeight - container.scrollTop - container.clientHeight < 60;
                    msgs.forEach(msg => {
                        container.appendChild(renderMessage(msg));
                        lastId = Math.max(lastId, msg.id_message);
                    });
                    if (wasAtBottom) scrollToBottom();
                }
            } catch (_) { }
        }

        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const message = input.value.trim();
            if (!message) return;

            input.value = '';
            input.focus();

            await fetch('/?page=admin-admin/chat/send', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'message=' + encodeURIComponent(message)
            });

            await poll();
        });

        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                form.dispatchEvent(new Event('submit'));
            }
        });

        scrollToBottom();
        setInterval(poll, 2000);
    })();
</script>