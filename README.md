<p align="center">
  <a href="https://elementor.com" target="_blank">
    <img src="https://www.wpcrafter.com/wp-content/uploads/2017/08/elementor_logo.png" width="200" alt="Elementor">
  </a>
</p>

# About the Project

This WordPress theme is built with Elementor and includes the following features:

**Home Page**
- Custom Elementor addon: **Product Gallery Widget**
- Fetches products and collections from the [DummyJSON API](https://dummyjson.com)
- Product grid displays image, title, price, and category
- AJAX sorting and filtering by category
- "View Detail" button navigates to the single product page

**Single Product Page**
- Custom single template with dynamic routing to SEO-friendly URLs based on product ID
- Displays image gallery and related products

## Author

- [Foo Fung](https://github.com/minhphueng) | email: minhphueng@gmail.com

## License

- MIT. Please see the [License File](/LICENSE) for more information.
- Sample images are provided by https://www.pexels.com
  
# Development

## 1. Prerequisites
- Latest version of WordPress
- PHP 7.4 or higher
- [DummyJSON API](https://dummyjson.com)
- Elementor Page Builder (free version) — [elementor.com](https://elementor.com/)

## 2. Set Up a Blank WordPress Site
- Install the [hello-elementor] theme and [elementor-demo] plugin from this repository
- Import sample data (Frontpage content) from [sample-data.xml](https://github.com/minhphueng/elementor-demo/blob/main/sample-data.xml)
- Change the custom permalink structure to `/%category%/%postname%/`

## 3. Try the Demo
- Demo URL: https://foofung.byethost33.com/
- Admin URL: https://foofung.byethost33.com/backend
- Login credentials:
  Username: `guest`
  Password: `@demo6688#`
- Open the Front Page in the Elementor Editor and select "Product Gallery" from the widget sidebar
  
<img src="http://foofung.byethost33.com/wp-content/uploads/2025/08/how-to.jpg" alt="how to use the plugin" width="1024">

## 4. Screenshots
### Mobile
| Front-page                       | Detail page                       |
|----------------------------------|-----------------------------------|
| ![mobile front-page](http://foofung.byethost33.com/wp-content/uploads/2025/08/mobile-home.jpg) | ![mobile detail page](http://foofung.byethost33.com/wp-content/uploads/2025/08/mobile-detail.jpg) |


### Tablet
| Front-page                       | Detail page                       |
|----------------------------------|-----------------------------------|
| ![tablet front-page](http://foofung.byethost33.com/wp-content/uploads/2025/08/tablet-home.jpg) | ![tablet detail page](http://foofung.byethost33.com/wp-content/uploads/2025/08/tablet-detail.jpg) |


### Desktop
| Front-page                       | Detail page                       |
|----------------------------------|-----------------------------------|
| ![desktop front-page](http://foofung.byethost33.com/wp-content/uploads/2025/08/desktop-home.jpg) | ![desktop detail page](http://foofung.byethost33.com/wp-content/uploads/2025/08/desktop-detail.jpg) |


---

That's it. Happy coding!
