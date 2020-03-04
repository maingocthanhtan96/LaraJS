{{-- For Horizontal submenu --}}
<ul class="dropdown-menu">
  @foreach($menu as $submenu)
  <?php
      $custom_classes = "";
      if(isset($submenu->classlist)) {
          $custom_classes = $submenu->classlist;
      }
      $submenuTranslation = "";
      if(isset($menu->i18n)){
          $submenuTranslation = $menu->i18n;
      }
  ?>
  <li
    class="{{ (request()->is($submenu->url)) ? 'active' : '' }} {{ (isset($submenu->submenu)) ? "dropdown dropdown-submenu" : '' }} {{ $custom_classes }}">
    <a href="{{ $submenu->url }}" class="dropdown-item {{ (isset($submenu->submenu)) ? "dropdown-toggle" : '' }}"
      {{ (isset($submenu->submenu)) ? 'data-toggle=dropdown' : '' }}>
      <i class="{{ isset($submenu->icon) ? $submenu->icon : "" }}"></i>
      <span data-i18n="{{ $submenuTranslation }}">{{ $submenu->name }}</span>
    </a>
    @if (isset($submenu->submenu))
    @include('panels/horizontalSubmenu', ['menu' => $submenu->submenu])
    @endif
  </li>
  @endforeach
</ul>