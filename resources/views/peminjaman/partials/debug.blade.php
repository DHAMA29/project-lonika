<!-- Simple Test to verify the fix -->
<script>
// Test if the category filtering is working
setTimeout(() => {
    console.log('=== CATEGORY FILTER TEST ===');
    console.log('Categories found:', $('.category-card-simple[data-category]').length);
    console.log('Products found:', $('.product-item').length);
    
    $('.category-card-simple[data-category]').each(function() {
        console.log('Category:', $(this).data('category'), 'Text:', $(this).find('h6').text());
    });
    
    $('.product-item').each(function() {
        console.log('Product category:', $(this).data('category'));
    });
}, 2000);
</script>