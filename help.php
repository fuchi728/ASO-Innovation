<?php
$css_files = ['main-style.css'];
require 'header.php';
?>
<?php require 'header-menu.php'; ?>

<div id="help_app" class="content">
    <h1>お問い合わせ</h1>
    <p> ご質問・ご相談は以下のフォームよりご送信ください。</p>
    <form ref="helpForm" action="help-insert.php" method="post" @submit.prevent="handleSubmit">

        <div class="field">
            <label class="label">お名前</label>
            <div class="control">
                <input name="name" v-model="name" @input="clearError('name')" class="input" type="text" placeholder="氏名を入力してください">
            </div>
            <p
                v-if="submitted && errors.name"
                class="error has-text-danger"
            >＊お名前を入力してください</p>
        </div>

        <div class="field">
            <label class="label">メールアドレス</label>
            <div class="control">
                <input name="email" v-model="email" @input="clearError('email')" class="input" type="email" placeholder="メールアドレスを入力してください">
            </div>
            <p
                v-if="submitted && errors.email"
                class="error has-text-danger"
            >＊メールアドレスを入力してください</p>
        </div>

        <div class="field">
            <div class="control">
                <div class="field">
                    <label class="radio">
                        <input v-model="radio" @change="clearError('radio')" type="radio" name="help" value="delete">
                        アカウントを削除したい
                    </label>
                </div>
                <div class="field">
                    <label class="radio">
                        <input v-model="radio" @change="clearError('radio')" type="radio" name="help" value="other">
                        その他のお問い合わせ
                    </label>
                </div>
                <p
                    v-if="submitted && errors.radio"
                    class="error has-text-danger"
                >＊お問い合わせの種類を選択してください</p>
            </div>
        </div>

        <div class="field">
            <label class="label">{{helpType}}</label>
            <textarea name="textarea" v-model="textarea" @input="clearError('textarea')" class="textarea is-normal" :placeholder="textareaPlaceholder" :disabled="!radio"></textarea>
        </div>
        <p
            v-if="submitted && errors.textarea"
            class="error has-text-danger"
        >＊内容を入力してください</p>

        <button id="button" type="submit" class="button is-fullwidth">送信</button>


    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2.7.11/dist/vue.js"></script>
<script src="./script/help.js"></script>

<?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>