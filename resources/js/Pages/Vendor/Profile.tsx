import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import {Head} from "@inertiajs/react";
import {PageProps, PaginationProps, Product, Vendor} from "@/types";
import ProductItem from "@/Components/app/ProductItem";

function Profile(
  {vendor, products}: PageProps<{vendor: Vendor, products: PaginationProps<Product>}>
) {
  // console.log(products);
  return (
    <AuthenticatedLayout>
      <Head title={vendor.store_name + ' Profile Page'}/>
      <div className="hero min-h-[320px] mb-8" style={{backgroundImage: "url(https://img.daisyui.com/images/stock/photo-1507358522600-9f71e620c44e.webp)",}}>
        <div className="hero-overlay bg-opacity-60"></div>
        <div className="hero-content text-neutral-content text-center">
          <div className="max-w-md">
            <h1 className="mb-5 text-5xl font-bold">
              {vendor.store_name}
            </h1>
          </div>
        </div>
      </div>

      <div className="container mx-auto pb-8">

        <div className="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3 px-8">
          {products.data.map(product=>(
            <ProductItem product={product} key={product.id}/>
          ))}
        </div>
      </div>
    </AuthenticatedLayout>
  );
}

export default Profile;
