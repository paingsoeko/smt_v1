<?php

namespace App;

enum MajorType: string
{
    case ပထဝီ = 'ပထဝီ';       // Geography
    case သမိုင်း = 'သမိုင်း';       // History
    case ဥပဒေ = 'ဥပဒေ';         // Law
    case စီးပွားစီမံ = 'စီးပွားစီမံ'; // Management
    case စီးပွားရေး = 'စီးပွားရေး'; // Economics
    case ဒဿနိက = 'ဒဿနိက';       // Philosophy
    case မြန်မာ = 'မြန်မာ';       // Myanmar
    case အင်္ဂလိပ် = 'အင်္ဂလိပ်'; // English
    case စိတ်ပညာ = 'စိတ်ပညာ';    // Psychology
    case အရှေ့တိုင်း = 'အရှေ့တိုင်း'; // Eastern Studies
    case သင်္ချာ = 'သင်္ချာ';     // Mathematics
    case ဓာတုဗေဒ = 'ဓာတုဗေဒ'; // Chemistry
    case ရူပ = 'ရူပ';           // Physics
    case သတ္တု = 'သတ္တု';       // Biology
    case ရုခ = 'ရုခ';           // Zoology
    case အခြား = 'အခြား';       // Other
}

