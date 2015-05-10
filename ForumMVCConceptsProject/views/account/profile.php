<div class="profile-box">
    <img src="/<?= $this->user['picture_url'] ?>" width="200" height="200">
    <div class="profile-element"><span class="profile-element-label">Username:</span> <?= htmlspecialchars($this->user['username']) ?></div>
    <div class="profile-element"><span class="profile-element-label">Firstname:</span> <?= htmlspecialchars($this->user['firstname']) ?></div>
    <div class="profile-element"><span class="profile-element-label">Lastname:</span> <?= htmlspecialchars($this->user['lastname']) ?></div>
    <div class="profile-element"><span class="profile-element-label">Email:</span> <?= htmlspecialchars($this->user['email']) ?></div>
</div>