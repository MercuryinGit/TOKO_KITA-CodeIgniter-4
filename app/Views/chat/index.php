<?= $this->include('layout/header') ?>
<?php
$contacts = $contacts ?? [];
$messages = $messages ?? [];
$contact = $contact ?? null;
$lastMessage = !empty($messages) ? end($messages) : null;
?>
<div class="chat-layout <?= $contact ? 'has-contact' : 'contact-list-mode' ?>">
    <aside class="chat-contacts">
        <div class="eyebrow">Komunikasi</div>
        <h1 class="h3 mb-4">Pesan</h1>
        <?php foreach ($contacts as $item): ?>
            <a class="chat-contact <?= ($contact['id'] ?? 0) == $item['id'] ? 'active' : '' ?>" href="<?= base_url('chat/' . $item['id']) ?>">
                <span class="chat-avatar"><i class="bi bi-person"></i></span>
                <span class="flex-grow-1"><strong><?= esc($item['nama']) ?></strong><small><?= esc($item['pesan_terakhir'] ?? 'Mulai percakapan') ?></small></span>
                <span class="online-dot <?= !empty($item['last_seen_at']) && strtotime($item['last_seen_at']) >= time() - 300 ? 'is-online' : '' ?>"></span>
            </a>
        <?php endforeach; ?>
    </aside>
    <section class="chat-window">
        <?php if ($contact): ?>
            <header class="chat-header"><a href="<?= base_url('chat') ?>" class="chat-back btn btn-sm btn-outline-primary"><i class="bi bi-arrow-left"></i><span>Kembali</span></a><div class="chat-avatar"><i class="bi bi-person"></i></div><div><strong><?= esc($contact['nama']) ?></strong><small class="d-block text-secondary"> <?= !empty($contact['last_seen_at']) && strtotime($contact['last_seen_at']) >= time() - 300 ? 'Online' : 'Offline' ?></small></div><a href="<?= base_url('profil/' . $contact['id']) ?>" class="ms-auto btn btn-sm btn-outline-primary">Profil</a></header>
            <div class="chat-messages" id="chatMessages" data-contact-id="<?= (int) $contact['id'] ?>" data-last-message="<?= (int) ($lastMessage['id_pesan'] ?? 0) ?>">
                <?php foreach ($messages as $message): ?>
                    <div class="chat-bubble <?= (int) $message['id_pengirim'] === (int) session()->get('user_id') ? 'mine' : 'theirs' ?>" data-message-id="<?= (int) $message['id_pesan'] ?>">
                        <?= nl2br(esc($message['pesan'])) ?><small><?= esc(date('H:i', strtotime($message['created_at']))) ?></small>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($messages)): ?><div class="empty-state" id="emptyChat"><i class="bi bi-chat-heart"></i><p>Belum ada pesan. Sapa user ini.</p></div><?php endif; ?>
            </div>
            <form action="<?= base_url('chat/' . $contact['id'] . '/send') ?>" method="post" class="chat-composer" id="chatComposer"><input name="pesan" class="form-control" placeholder="Tulis pesan..." maxlength="2000" autocomplete="off" required><button class="btn btn-primary" type="submit" aria-label="Kirim pesan"><i class="bi bi-send"></i></button></form>
        <?php else: ?>
            <div class="chat-empty"><i class="bi bi-chat-square-heart"></i><h2>Pilih percakapan</h2><p>Mulai komunikasi dengan user lain dari halaman profil mereka.</p></div>
        <?php endif; ?>
    </section>
</div>
<?php if ($contact): ?>
<script>
(() => {
    const messageList = document.getElementById('chatMessages');
    const composer = document.getElementById('chatComposer');
    const contactId = messageList.dataset.contactId;
    let lastMessageId = Number(messageList.dataset.lastMessage || 0);

    const addMessages = (messages) => {
        messages.forEach((message) => {
            if (document.querySelector(`[data-message-id="${message.id_pesan}"]`)) return;
            const bubble = document.createElement('div');
            bubble.className = `chat-bubble ${Number(message.id_pengirim) === <?= (int) session()->get('user_id') ?> ? 'mine' : 'theirs'}`;
            bubble.dataset.messageId = message.id_pesan;
            bubble.textContent = message.pesan;
            const time = document.createElement('small');
            time.textContent = new Date(message.created_at.replace(' ', 'T')).toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'});
            bubble.appendChild(time);
            messageList.appendChild(bubble);
            document.getElementById('emptyChat')?.remove();
            lastMessageId = Math.max(lastMessageId, Number(message.id_pesan));
        });
        if (messages.length) messageList.scrollTop = messageList.scrollHeight;
    };

    composer.addEventListener('submit', async (event) => {
        event.preventDefault();
        const input = composer.querySelector('input[name="pesan"]');
        const message = input.value.trim();
        if (!message) return;

        const button = composer.querySelector('button');
        button.disabled = true;
        try {
            const response = await fetch(composer.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                body: new URLSearchParams({pesan: message})
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.error || 'Pesan gagal dikirim.');
            input.value = '';
            addMessages([data.message]);
            input.focus();
        } catch (error) {
            window.alert(error.message);
        } finally {
            button.disabled = false;
        }
    });

    const pollMessages = async () => {
        try {
            const response = await fetch(`<?= base_url('chat') ?>/${contactId}/messages?after=${lastMessageId}`, {headers: {'Accept': 'application/json'}});
            if (response.ok) addMessages((await response.json()).messages || []);
        } catch (error) {
            // Polling akan mencoba lagi pada interval berikutnya.
        }
    };

    messageList.scrollTop = messageList.scrollHeight;
    setInterval(pollMessages, 2000);
})();
</script>
<?php endif; ?>
<?= $this->include('layout/footer') ?>
