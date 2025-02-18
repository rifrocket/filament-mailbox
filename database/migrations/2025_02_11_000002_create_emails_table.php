<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailsTable extends Migration
{
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('folder_id')->nullable()->index();
            // Unique identifier from the IMAP server for this email.
            $table->string('uid')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            // Optional IMAP message ID for additional reference.
            $table->string('message_id')->nullable()->index();
            $table->string('subject')->nullable();
            $table->string('from')->nullable();
            // Storing multiple recipients as JSON text.
            $table->text('to')->nullable();
            $table->text('cc')->nullable();
            $table->text('bcc')->nullable();
            // Email content (HTML or plain text).
            $table->longText('body')->nullable();
            // Flag to indicate if the email has been read.
            $table->boolean('seen')->default(false);
            // The date when the email was sent.
            $table->timestamp('email_date')->nullable();
            // Additional flags from the IMAP server (stored as JSON).
            $table->json('flags')->nullable();
            $table->timestamps();

            // Set up a foreign key to the folders table.
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('emails');
    }
}

