import React from 'react';
import {GroupedCartItems, PageProps} from "@/types";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import {Head, Link} from "@inertiajs/react";

function Index(
  {
    csrf_token,
    totalQuantity,
    totalPrice,
    cartItems
  }: PageProps<{cartItems: Record<number, GroupedCartItems>}>
) {
  return (
    <AuthenticatedLayout>
      <Head title={'Your Carts'}/>

      <div className="container mx-auto p-8 flex flex-col lg:flex-row">
        <div className="card flex1 bg-white dark:bg-gray-800 order-2 lg:order-1">
          <div className="card-body">
            <h2 className="text-lg font-bold">Shopping Cart</h2>
            <div className="my-4">
              {Object.keys(cartItems).length===0&&(
                <div className='py-2 text-gray-500 text-center'>
                  You Don't have Any Items Yet!
                </div>
              )}
              {Object.values(cartItems).map(cartItem=>(
                <div key={cartItem.id}>
                  <div className="flex items-center justify-between pb-4 border-b border-gray-300 mb-4">
                    <Link href='/' className='underline'>
                      {cartItem.user.name}
                    </Link>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>
        <div className="card bg-white dark:bg-gray-800 lg:min-w-[260px] order1 lg:order-2">
          <div className="card-body"></div>
        </div>
      </div>
    </AuthenticatedLayout>
  );
}

export default Index;
