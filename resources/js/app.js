import './bootstrap';
import 'laravel-datatables-vite';

import Alpine from 'alpinejs';
import Image from './image'
import UserIndex from './users/Index'



window.Alpine = Alpine;


Alpine.data('photo', Image);
Alpine.data('userIndex', UserIndex);
Alpine.start();
